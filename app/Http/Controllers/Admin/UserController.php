<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $users = User::where('role', 'siswa')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function approve(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $user->status_akun = 'aktif';
        $user->save();
        try {
            \App\Services\WhatsAppService::send($user->phone, "Akun Anda telah disetujui. Anda sekarang dapat login.");
        } catch (\Exception $e) {}

        return back()->with('success', 'Akun disetujui');
    }

    public function reject(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $user->status_akun = 'ditolak';
        $user->save();
        try {
            \App\Services\WhatsAppService::send($user->phone, "Akun Anda telah ditolak oleh admin.");
        } catch (\Exception $e) {}

        return back()->with('success', 'Akun ditolak');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,siswa',
            'status_akun' => 'required|in:menunggu,aktif,ditolak',
        ]);
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        User::create($data);
        return back()->with('success', 'User dibuat');
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,siswa',
            'status_akun' => 'required|in:menunggu,aktif,ditolak',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return back()->with('success', 'User diperbarui');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $user->delete();
        return back()->with('success', 'User dihapus');
    }
}
