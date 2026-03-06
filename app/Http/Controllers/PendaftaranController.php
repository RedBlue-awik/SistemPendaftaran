<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Gelombang;
use App\Models\Jalur;
use App\Models\Pendaftaran;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $gelombangs = Gelombang::where('status', 'aktif')->get();
        $jalurs = Jalur::all();
        return view('pendaftaran', compact('user', 'gelombangs', 'jalurs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'gelombang_id' => 'required|exists:gelombangs,id',
            'jalur_id' => 'required|exists:jalurs,id',
            'nama_lengkap' => 'required|string',
            'nik' => 'required|string|unique:pendaftarans,nik',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'sekolah_asal' => 'required|string',
            'jurusan_pilihan' => 'required|string',
            'kk' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'akta' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'ijazah' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'foto' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'sertifikat' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'ktp_orangtua' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $dataPendaftaran = $validatedData;
            $fileKeys = ['kk', 'akta', 'ijazah', 'foto', 'sertifikat', 'ktp_orangtua'];
            
            foreach ($fileKeys as $key) {
                unset($dataPendaftaran[$key]);
            }

            $dataPendaftaran['user_id'] = Auth::id();
            $dataPendaftaran['nomor_pendaftaran'] = 'P' . time() . uniqid();
            $dataPendaftaran['status_pendaftaran'] = 'menunggu_verifikasi';

            $pendaftaran = Pendaftaran::create($dataPendaftaran);

            $dataDokumen = ['pendaftaran_id' => $pendaftaran->id];

            foreach ($fileKeys as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    $path = $request->file($fileKey)->store('dokumen', 'public');
                    $dataDokumen[$fileKey] = $path;
                }
            }

            Dokumen::create($dataDokumen);

            try {
                $to = $pendaftaran->no_hp ?? Auth::user()->phone;
                if ($to) {
                    WhatsAppService::send($to, 'Pendaftaran dan dokumen Anda berhasil disimpan dengan No. Pendaftaran: ' . $pendaftaran->nomor_pendaftaran . '. Menunggu verifikasi admin.');
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp Error: ' . $e->getMessage());
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Pendaftaran berhasil disimpan.',
                    'redirect' => route('pendaftaran')
                ]);
            }

            return redirect()->route('pendaftaran')->with('success', 'Pendaftaran dan dokumen berhasil disimpan. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pendaftaran Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}