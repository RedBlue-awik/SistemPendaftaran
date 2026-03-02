<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jalur extends Model
{
    protected $fillable = [
        'nama_jalur',
        'deskripsi',
        'batas_pendaftaran',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'jalur_id');
    }
}
