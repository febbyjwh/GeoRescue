<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FasilitasVital extends Model
{
    protected $table = 'fasilitas_vital';

    protected $fillable = [
        'desa_id',
        'kecamatan_id',
        'nama_fasilitas',
        'jenis_fasilitas',
        'alamat',
        'latitude',
        'longitude',
        'status'
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
