<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'kk',
        'akta',
        'ijazah',
        'foto',
        'kip',
        'ktp_orangtua',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
