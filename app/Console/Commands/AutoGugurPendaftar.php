<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pendaftaran;
use App\Models\Pengaturan;
use App\Models\Gelombang;
use Carbon\Carbon;

class AutoGugurPendaftar extends Command
{
    protected $signature = 'pmb:auto-gugur';
    protected $description = 'Ubah status pendaftar menjadi gugur jika sudah melewati batas daftar ulang';

    public function handle()
    {
        $pengaturan = Pengaturan::whereNotNull('tanggal_selesai')->first();

        if (! $pengaturan || ! $pengaturan->tanggal_mulai || ! $pengaturan->tanggal_selesai) {
            $this->info('Tanggal mulai/selesai daftar ulang belum diatur di Pengaturan. Tidak ada aksi.');
            return 0;
        }

        $now = Carbon::now();
        $tanggalSelesai = Carbon::parse($pengaturan->tanggal_selesai);

        if ($now->lessThanOrEqualTo($tanggalSelesai)) {
            return 0;
        }

        $activeGelombang = Gelombang::where('status', 'aktif')->first();

        if (! $activeGelombang) {
            return 0;
        }

        $list = Pendaftaran::where('status_kelulusan', 'lulus')
            ->where('status_daftar_ulang', 'belum')
            ->where('gelombang_id', $activeGelombang->id)
            ->get();

        foreach ($list as $p) {
            $p->status_akhir = 'gugur';
            $p->save();
            $this->info("Pendaftar {$p->id} gugur");
        }

        return 0;
    }
}
