<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Dokumen;
use App\Models\Gelombang;
use App\Models\Jalur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class PendaftaranController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $gelombangs = Gelombang::where('status', 'aktif')->get();
        $jalurs = Jalur::all();
        return view('pendaftaran.create', compact('user', 'gelombangs', 'jalurs'));
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
            'jalur_id' => 'required|exists:jalurs,id',
            'nama_lengkap' => 'required|string',
            'nik' => 'required|string|unique:pendaftarans,nik',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'sekolah_asal' => 'required|string',
            'jurusan_pilihan' => 'required|string',
        ]);

        // Pass data to confirmation view (no save yet)
        $gelombang = Gelombang::find($data['gelombang_id']);
        $jalur = Jalur::find($data['jalur_id']);

        return view('pendaftaran.confirm', compact('data', 'gelombang', 'jalur'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
            'jalur_id' => 'required|exists:jalurs,id',
            'nama_lengkap' => 'required|string',
            'nik' => 'required|string|unique:pendaftarans,nik',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'sekolah_asal' => 'required|string',
            'jurusan_pilihan' => 'required|string',
        ]);

        $data['user_id'] = Auth::id();
        $data['nomor_pendaftaran'] = 'P'.time().uniqid();
        $data['status_pendaftaran'] = 'menunggu_verifikasi';

        $pendaftaran = Pendaftaran::create($data);

        try {
            $u = Auth::user();
            if ($u && !empty($u->phone)) {
                WhatsAppService::send($u->phone, "Biodata Anda berhasil disimpan. Silakan lanjutkan upload dokumen.");
            }
        } catch (\Exception $e) {}

        return redirect()->route('pendaftaran.upload', $pendaftaran->id)->with('success', 'Simpan biodata berhasil. Silakan upload dokumen.');
    }

    public function uploadForm(Pendaftaran $pendaftaran)
    {
        if (Auth::id() !== $pendaftaran->user_id) abort(403);
        return view('pendaftaran.upload', compact('pendaftaran'));
    }

    public function uploadDokumen(Request $request, Pendaftaran $pendaftaran)
    {
        if (Auth::id() !== $pendaftaran->user_id) abort(403);

        $data = $request->validate([
            'kk' => 'required|file|max:2048',
            'akta' => 'required|file|max:2048',
            'ijazah' => 'required|file|max:2048',
            'foto' => 'required|image|max:2048',
            'sertifikat' => 'nullable|file|max:2048',
            'ktp_orangtua' => 'nullable|file|max:2048',
        ]);

        $files = [];
        foreach (['kk','akta','ijazah','foto','sertifikat','ktp_orangtua'] as $key) {
            if ($request->hasFile($key)) {
                $files[$key] = $request->file($key)->store('dokumens','public');
            }
        }

        $dok = Dokumen::create(array_merge(['pendaftaran_id' => $pendaftaran->id], $files));

        $pendaftaran->status_pendaftaran = 'menunggu_verifikasi';
        $pendaftaran->save();

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil diupload, menunggu verifikasi admin.');
    }
}
