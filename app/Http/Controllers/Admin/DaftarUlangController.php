<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarUlangController extends Controller
{
    public function confirm(Pendaftaran $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $pendaftaran->status_daftar_ulang = 'sudah';
        $pendaftaran->status_akhir = 'resmi';
        $pendaftaran->tanggal_daftar_ulang = now();
        $pendaftaran->save();

        try {
            \App\Services\WhatsAppService::send($pendaftaran->user->phone, "Konfirmasi daftar ulang Anda telah diterima. Status Anda: resmi.");
        } catch (\Exception $e) {}

        return back()->with('success', 'Konfirmasi daftar ulang berhasil');
    }
}
