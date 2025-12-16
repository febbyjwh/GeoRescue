<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoskoBencana extends Model
{
    protected $table = 'posko_bencana'; 
    protected $fillable = [
        'nama_posko',
        'jenis_posko',
        'alamat_posko',
        'nama_desa',
        'kecamatan',
        'latitude',
        'longitude',
        'status_posko'
    ];
}
