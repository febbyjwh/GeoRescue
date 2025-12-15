<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurEvakuasi extends Model
{
    protected $fillable = ['nama_jalur', 'geojson', 'created_by'];
}
