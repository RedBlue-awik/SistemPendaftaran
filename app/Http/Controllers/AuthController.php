<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
        ]);

        // normalize and validate phone using libphonenumber if available
        $phoneToStore = $data['phone'];
        try {
            if (class_exists('\\libphonenumber\\PhoneNumberUtil')) {
                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $numberProto = $phoneUtil->parse($data['phone'], 'ID');
                if (! $phoneUtil->isValidNumber($numberProto)) {
                    return back()->withErrors(['phone' => 'Format nomor telepon tidak valid'])->withInput();
                }
                $phoneToStore = $phoneUtil->format($numberProto, \libphonenumber\PhoneNumberFormat::E164);
            }
        } catch (\Throwable $e) {
            // if lib not available or parse fails, fallback to raw input
            $phoneToStore = $data['phone'];
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $phoneToStore,
            'role' => 'siswa',
            'status_akun' => 'menunggu',
        ]);

        // send WA notification (best-effort)
        try {
            \App\Services\WhatsAppService::send($user->phone, "Terima kasih $user->name, registrasi Anda berhasil. Menunggu verifikasi admin.");
        } catch (\Exception $e) {
            // ignore
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Menunggu verifikasi admin.');
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
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Credensial tidak cocok'])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->status_akun !== 'aktif') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun belum aktif atau ditolak']);
        }
        // optionally notify login via WA (non-blocking)
        try {
            \App\Services\WhatsAppService::send($user->phone, "Halo $user->name, Anda berhasil login.");
        } catch (\Exception $e) {}

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('pendaftaran.create');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
