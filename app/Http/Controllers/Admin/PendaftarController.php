<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendaftarController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $query = Pendaftaran::with('user','gelombang','jalur','dokumen')->orderBy('created_at','desc');

        // filter: gelombang (jika ada)
        if (request()->has('gelombang') && request('gelombang') != '') {
            $query->where('gelombang_id', request('gelombang'));
        }

        // filter: selesai (status_akhir = 'resmi')
        if (request()->has('status_siswa') && request('status_siswa') === 'resmi') {
            $query->where('status_akhir', 'resmi');
        }

        // filter: status daftar ulang (jika ada)
        if (request()->has('daftar_ulang') && request('daftar_ulang') != '') {
            $query->where('status_daftar_ulang', request('daftar_ulang'));
        }

        $pendaftarans = $query->get();
        $pengaturan = Pengaturan::all();

        // load gelombangs for filter dropdown
        $gelombangs = \App\Models\Gelombang::orderBy('tanggal_mulai','desc')->get();

        return view('admin.pendaftars', compact('pendaftarans','pengaturan','gelombangs'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        return view('admin.pendaftaran_show', compact('pendaftaran'));
    }


    public function setSelection(Pendaftaran $pendaftaran, Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $data = $request->validate([
            'status_kelulusan' => 'required|in:menunggu,lulus,tidak_lulus',
            'batas_daftar_ulang' => 'nullable|date',
        ]);

        $pendaftaran->status_kelulusan = $data['status_kelulusan'];
        
        if (!empty($data['batas_daftar_ulang'])) {
            $pendaftaran->batas_daftar_ulang = $data['batas_daftar_ulang'];
        }

        if ($pendaftaran->status_kelulusan === 'lulus') {
            $pendaftaran->status_daftar_ulang = 'belum';
            $pendaftaran->status_akhir = 'calon';
        } elseif ($pendaftaran->status_kelulusan === 'tidak_lulus') {
            $pendaftaran->status_akhir = 'gugur';
        }

        $pendaftaran->save();

        // LOGIKA KIRIM WA
        try {
            $to = !empty($pendaftaran->no_hp) ? $pendaftaran->no_hp : ($pendaftaran->user->phone ?? null);
            
            if (!empty($to)) {
                if ($pendaftaran->status_kelulusan === 'lulus') {
                    // Panggil fungsi detail
                    $this->kirimPesanKelulusan($pendaftaran);
                } elseif ($pendaftaran->status_kelulusan === 'tidak_lulus') {
                    WhatsAppService::send($to, "Mohon maaf, Anda dinyatakan TIDAK LULUS pada seleksi penerimaan peserta didik baru.");
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA: ' . $e->getMessage());
        }

        return back()->with('success','Status seleksi diperbarui dan notifikasi terkirim.');
    }

    public function confirmDaftarUlang(Pendaftaran $pendaftaran, Request $request)
    {
        // Admin dapat mengatur status daftar ulang menjadi 'sudah' atau 'belum' via modal
        $status = $request->input('status', 'sudah');
        if (!in_array($status, ['sudah', 'belum'])) {
            $status = 'sudah';
        }

        $update = ['status_daftar_ulang' => $status];

        if ($status === 'sudah') {
            $update['tanggal_daftar_ulang'] = now();
            $update['status_akhir'] = 'resmi';
        } else {
            $update['tanggal_daftar_ulang'] = null;
            // jika sebelumnya lulus, kembalikan ke 'calon' agar tetap dalam alur seleksi
            if ($pendaftaran->status_kelulusan === 'lulus') {
                $update['status_akhir'] = 'calon';
            }
        }

        $pendaftaran->update($update);

        return back()->with('success', 'Status daftar ulang diperbarui.');
    }

    protected function kirimPesanKelulusan($pendaftaran)
    {
        $pengaturan = Pengaturan::first();

        if ($pengaturan) {
            $pesan = "Selamat! Anda dinyatakan *LULUS* pada seleksi penerimaan peserta didik baru.\n\n";
            $pesan .= "Nama: {$pendaftaran->nama_lengkap}\n";
            $pesan .= "No Pendaftaran: {$pendaftaran->nomor_pendaftaran}\n\n";
            $pesan .= "*Informasi Daftar Ulang:*\n";
            $pesan .= "Lokasi: {$pengaturan->lokasi}\n";
            
            \Carbon\Carbon::setLocale('id');
            $tglMulai = \Carbon\Carbon::parse($pengaturan->tanggal)->format('d F Y');
            $tglSelesai = isset($pengaturan->tanggal_selesai) ? \Carbon\Carbon::parse($pengaturan->tanggal_selesai)->format('d F Y') : $tglMulai;
            $jam = \Carbon\Carbon::parse($pengaturan->jam)->format('H:i');
            
            $pesan .= "Tanggal: {$tglMulai} - {$tglSelesai}\n";
            $pesan .= "Jam: {$jam}\n";
            $pesan .= "Persyaratan: {$pengaturan->persyaratan}\n\n";
            $pesan .= "Harap hadir tepat waktu.";

            try {
                $to = $pendaftaran->no_hp ?: ($pendaftaran->user->phone ?? null);
                if ($to) {
                    WhatsAppService::send($to, $pesan);
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA kelulusan: ' . $e->getMessage());
            }
        }
    }
}
