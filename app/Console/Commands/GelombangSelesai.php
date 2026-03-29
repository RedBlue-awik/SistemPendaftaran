<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gelombang;
use App\Models\Pendaftaran;

class GelombangSelesai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pmb:gelombang-selesai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai pendaftar pada gelombang yang sudah tutup sebagai selesai (status_akhir => resmi)';

    public function handle()
    {
        $gelombangId = $this->option('gelombang');

        $query = Gelombang::query();
        if ($gelombangId) $query->where('id', $gelombangId);
        $query->where('status', 'tutup');

        $gelombangs = $query->get();

        if ($gelombangs->isEmpty()) {
            $this->info('Tidak ada gelombang bertatus tutup untuk diproses.');
            return 0;
        }

        foreach ($gelombangs as $g) {
            $count = Pendaftaran::where('gelombang_id', $g->id)
                ->where('status_akhir', 'calon')
                ->update(['status_akhir' => 'resmi']);

            $this->info("Gelombang {$g->id} ({$g->nama}): menandai {$count} pendaftar sebagai selesai (resmi).");
        }

        return 0;
    }
}
