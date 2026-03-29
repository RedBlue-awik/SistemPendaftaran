<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengaturans')->insert([
            'id' => 1,
            'lokasi' => 'Depan Kantor Guru',
            'tanggal_mulai' => '2026-03-14',
            'tanggal_selesai' => '2026-03-28',
            'jam' => '08:15:00',
            'persyaratan' => "- (Foto copy) Ktp orangtua\r\n- (Foto copy) Kk\r\n- Kip (kalau ada)\r\n- Ijazah",
            'created_at' => '2026-03-21 06:19:18',
            'updated_at' => '2026-04-03 13:39:22',
        ]);
    }
}
