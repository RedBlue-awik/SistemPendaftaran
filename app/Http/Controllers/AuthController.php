<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', Password::min(8)->numbers()],
            'phone' => 'required|string|min:10|max:15',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.min' => 'Nomor HP minimal 10 digit.',
            'phone.max' => 'Nomor HP maksimal 15 digit.',
        ]);

        $phoneToStore = $data['phone'];
        
        // Normalisasi Nomor HP
        try {
            if (class_exists('\\libphonenumber\\PhoneNumberUtil')) {
                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $numberProto = $phoneUtil->parse($data['phone'], 'ID');
                if (!$phoneUtil->isValidNumber($numberProto)) {
                    return back()->withErrors(['phone' => 'Format nomor telepon tidak valid'])->withInput();
                }
                $phoneToStore = $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::E164);
            }
        } catch (\Throwable $e) {
            // Lewati jika error parsing
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $phoneToStore,
                'role' => 'siswa',
                'status_akun' => 'menunggu',
            ]);

            try {
                \App\Services\WhatsAppService::send($user->phone, "Terima kasih {$user->name}, registrasi Anda berhasil.");
            } catch (\Exception $e) {
                Log::warning('Gagal kirim WA Register: ' . $e->getMessage());
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Register Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')->withInput();
        }

        return redirect()
            ->route('register')
            ->with('login_success', [
                'message' => 'Registrasi berhasil. Silahkan Login.',
                'icon' => 'success',
                'redirect' => route('login'),
                'delay' => 1300,
            ]);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau Password salah'])
                ->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();
        
        if ($user->status_akun !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Akun belum aktif atau sudah nonaktif'])->withInput();
        }

        try {
            \App\Services\WhatsAppService::send($user->phone, "Halo {$user->name}, Anda berhasil login.");
        } catch (\Exception $e) {
            // Abaikan error WA
        }

        $redirectRoute = $user->role === 'admin' ? route('admin.dashboard') : route('pendaftaran.index');

        return redirect()
            ->route('login')
            ->with('login_success', [
                'message' => 'Login berhasil',
                'icon' => 'success',
                'redirect' => $redirectRoute,
                'delay' => 1300,
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}