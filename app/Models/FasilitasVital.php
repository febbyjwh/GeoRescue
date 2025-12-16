<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasVital extends Model
{
    protected $table = 'fasilitas_vital';

    protected $fillable = [
        'nama_fasilitas',
        'jenis_fasilitas',
        'alamat',
        'desa',
        'kecamatan',
        'latitude',
        'longitude',
        'status'
    ];
}
