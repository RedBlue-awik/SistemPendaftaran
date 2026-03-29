<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gelombang;

class AutoTutup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pmb:auto-tutup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ubah status gelombang menjadi tutup jika sudah melewati tanggal selesai atau kuota terpenuhi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $gelombangs = Gelombang::all();

        foreach ($gelombangs as $g) {
            if ($today < $g->tanggal_mulai) {
                $g->status = 'segera';
            } elseif ($today >= $g->tanggal_mulai && $today <= $g->tanggal_selesai) {
                $g->status = 'aktif';
            } elseif ($today > $g->tanggal_selesai) {
                $g->status = 'tutup';
            }

            if ($g->pendaftarans()->count() >= $g->batas_pendaftaran) {
                $g->status = 'tutup';
            }

            $g->save();
        }

        return 0;
    }
}
