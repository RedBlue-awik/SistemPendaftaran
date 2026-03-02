<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pendaftaran;

class AutoGugurPendaftar extends Command
{
    protected $signature = 'pmb:auto-gugur';
    protected $description = 'Set status_akhir to gugur for lulus but not daftar ulang after batas_daftar_ulang';

    public function handle()
    {
        $now = now();
        $list = Pendaftaran::where('status_kelulusan', 'lulus')
            ->where('status_daftar_ulang', 'belum')
            ->whereNotNull('batas_daftar_ulang')
            ->where('batas_daftar_ulang', '<', $now)
            ->get();

        foreach ($list as $p) {
            $p->status_akhir = 'gugur';
            $p->save();
            $this->info("Pendaftar {$p->id} gugur");
        }

        return 0;
    }
}
