<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Gelombang;
use App\Models\Jalur;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeGelombang = Gelombang::where('status', 'aktif')->first();
        $jalurs = Jalur::withCount('pendaftarans')->get();
        return view('pendaftaran', compact('user', 'activeGelombang', 'jalurs'));
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

        // Pastikan gelombang yang dipakai adalah gelombang aktif (user tidak memilihnya)
        $activeGelombang = Gelombang::where('status', 'aktif')->first();
        if (!$activeGelombang) {
            return back()->withInput()->with('error', 'Belum ada gelombang aktif saat ini. Silakan hubungi admin.');
        }
        $validatedData['gelombang_id'] = $activeGelombang->id;

        // Cek kuota jalur: jika sudah mencapai batas, batalkan pendaftaran
        $jalur = Jalur::withCount('pendaftarans')->find($validatedData['jalur_id']);
        if (!$jalur) {
            return back()->withInput()->withErrors(['jalur_id' => 'Jalur yang dipilih tidak ditemukan.']);
        }
        if (!is_null($jalur->batas_pendaftaran) && $jalur->pendaftarans_count >= $jalur->batas_pendaftaran) {
            return back()->withInput()->withErrors(['jalur_id' => 'Jalur yang dipilih sudah penuh. Silakan pilih jalur lain.']);
        }

        DB::beginTransaction();
        try {
            $dataPendaftaran = $validatedData;
            $fileKeys = ['kk', 'akta', 'ijazah', 'foto', 'ktp_orangtua', 'kip'];
            
            foreach ($fileKeys as $key) {
                unset($dataPendaftaran[$key]);
            }

            // Lock gelombang and jalur rows and decrement their batas_pendaftaran atomically
            $gelombang = Gelombang::lockForUpdate()->find($dataPendaftaran['gelombang_id']);
            if ($gelombang && !is_null($gelombang->batas_pendaftaran)) {
                if ($gelombang->batas_pendaftaran <= 0) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['gelombang_id' => 'Kuota gelombang tidak mencukupi.']);
                }
                $gelombang->batas_pendaftaran = max(0, $gelombang->batas_pendaftaran - 1);
                $gelombang->save();
            }

            $jalurLocked = Jalur::lockForUpdate()->find($dataPendaftaran['jalur_id']);
            if ($jalurLocked && !is_null($jalurLocked->batas_pendaftaran)) {
                if ($jalurLocked->batas_pendaftaran <= 0) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['jalur_id' => 'Kuota jalur tidak mencukupi. Silakan pilih jalur lain.']);
                }
                $jalurLocked->batas_pendaftaran = max(0, $jalurLocked->batas_pendaftaran - 1);
                $jalurLocked->save();
            }

            // Generate nomor pendaftaran per-gelombang (reset tiap gelombang)
            $seqRow = DB::table('pendaftarans')
                ->where('gelombang_id', $gelombang->id)
                ->lockForUpdate()
                ->selectRaw('COUNT(*) as cnt')
                ->first();

            $nextNumber = ($seqRow->cnt ?? 0) + 1;

            $dataPendaftaran['user_id'] = Auth::id();
            $dataPendaftaran['nomor_pendaftaran'] = 'P' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '-G' . $gelombang->id;
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

            // WhatsApp notification removed per request

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