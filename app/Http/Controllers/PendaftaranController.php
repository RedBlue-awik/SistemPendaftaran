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
    public function index()
    {
        $user = Auth::user();
        $gelombangs = Gelombang::where('status', 'aktif')->get();
        $jalurs = Jalur::all();
        return view('pendaftaran', compact('user', 'gelombangs', 'jalurs'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'unique' => ':attribute sudah terdaftar.',
            'mimes' => 'Format file :attribute harus :values.',
            'max' => 'Ukuran file :attribute maksimal :max KB.',
            'date' => 'Format tanggal tidak valid.',
        ];

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
            'ktp_orangtua' => 'required|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'kip' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
        ], $messages);

        DB::beginTransaction();
        try {
            $dataPendaftaran = $validatedData;
            $fileKeys = ['kk', 'akta', 'ijazah', 'foto', 'ktp_orangtua', 'kip'];
            
            foreach ($fileKeys as $key) {
                unset($dataPendaftaran[$key]);
            }

            $lastPendaftaran = Pendaftaran::lockForUpdate()->orderBy('id', 'desc')->first();
            $lastNumber = $lastPendaftaran ? (int)substr($lastPendaftaran->nomor_pendaftaran, 2) : 0;
            $nextNumber = $lastNumber + 1;
            
            $dataPendaftaran['user_id'] = Auth::id();
            $dataPendaftaran['nomor_pendaftaran'] = 'P-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
                    'redirect' => route('pendaftaran.index')
                ]);
            }

            return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil disimpan. Menunggu verifikasi admin.');

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