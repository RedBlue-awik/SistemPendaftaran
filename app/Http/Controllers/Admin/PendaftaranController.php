<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $pendaftarans = Pendaftaran::with('user','gelombang','jalur','dokumen')->orderBy('created_at','desc')->get();
        return view('admin.pendaftarans', compact('pendaftarans'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.pendaftaran_show', compact('pendaftaran'));
    }

    public function verify(Pendaftaran $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $pendaftaran->status_pendaftaran = 'terverifikasi';
        $pendaftaran->save();
        try {
            $to = !empty($pendaftaran->no_hp) ? $pendaftaran->no_hp : ($pendaftaran->user->phone ?? null);
            if (!empty($to)) {
                \App\Services\WhatsAppService::send($to, "Data pendaftaran Anda telah diverifikasi oleh admin.");
            }
        } catch (\Exception $e) {}

        return back()->with('success','Pendaftar terverifikasi');
    }

    public function setSelection(Pendaftaran $pendaftaran, Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'status_kelulusan' => 'required|in:proses,lulus,tidak_lulus',
            'batas_daftar_ulang' => 'nullable|date',
        ]);
        $pendaftaran->status_kelulusan = $data['status_kelulusan'];
        if (!empty($data['batas_daftar_ulang'])) $pendaftaran->batas_daftar_ulang = $data['batas_daftar_ulang'];

        // update related status fields for clarity
        if ($pendaftaran->status_kelulusan === 'lulus') {
            $pendaftaran->status_daftar_ulang = 'belum';
            $pendaftaran->status_akhir = 'calon';
        } elseif ($pendaftaran->status_kelulusan === 'tidak_lulus') {
            $pendaftaran->status_akhir = 'gugur';
        }

        $pendaftaran->save();
        try {
            $to = !empty($pendaftaran->no_hp) ? $pendaftaran->no_hp : ($pendaftaran->user->phone ?? null);
            if (!empty($to)) {
                if ($pendaftaran->status_kelulusan === 'lulus') {
                    \App\Services\WhatsAppService::send($to, "Selamat, Anda dinyatakan LULUS. Silakan periksa pengumuman untuk detail daftar ulang.");
                } elseif ($pendaftaran->status_kelulusan === 'tidak_lulus') {
                    \App\Services\WhatsAppService::send($to, "Mohon maaf, Anda dinyatakan TIDAK LULUS.");
                }
            }
        } catch (\Exception $e) {}

        return back()->with('success','Status seleksi diperbarui');
    }
}
