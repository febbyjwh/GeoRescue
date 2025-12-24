<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = "bencana";
    protected $fillable = [
        "kecamatan",
        "desa",
        "nama_bencana",
        "tingkat_kerawanan",
        "lang",
        "lat"
    ];
}
