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
        "kecamatan_id",
        "desa_id",
        'latitude',
        'longitude',
        'status_posko'
    ];

     public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'desa_id');
    }
}
