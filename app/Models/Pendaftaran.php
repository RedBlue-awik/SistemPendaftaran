<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $fillable = [
        'user_id',
        'gelombang_id',
        'jalur_id',
        'nomor_pendaftaran',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'sekolah_asal',
        'jurusan_pilihan',
        'status_pendaftaran',
        'status_kelulusan',
        'status_daftar_ulang',
        'tanggal_daftar_ulang',
        'status_akhir',
        'batas_daftar_ulang',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function jalur()
    {
        return $this->belongsTo(Jalur::class);
    }

    public function dokumen()
    {
        return $this->hasOne(Dokumen::class);
    }
}
