<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = "bencana";
    protected $fillable = [
        "kecamatan_id",
        "desa_id",
        "jenis_bencana",
        "tingkat_kerawanan",
        "nilai",
        "satuan",
        "status",
        "lang",
        "lat"
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
