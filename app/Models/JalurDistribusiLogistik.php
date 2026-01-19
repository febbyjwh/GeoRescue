<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurDistribusiLogistik extends Model
{
    protected $table = 'logistiks';

    protected $fillable = [
        'nama_lokasi',
        'district_id',
        'village_id',
        'jenis_logistik',
        'jumlah',
        'satuan',
        'status',
        'lat',
        'lng',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
