<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurDistribusiLogistik extends Model
{
    protected $table = 'jalur_distribusi_logistik';

    protected $fillable = [
        'nama_jalur',
        'asal_logistik',
        'asal_latitude',
        'asal_longitude',
        'tujuan_distribusi',
        'tujuan_latitude',
        'tujuan_longitude',
        'status_jalur'
    ];
}
