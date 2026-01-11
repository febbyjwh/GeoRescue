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
        'geojson',
        'lat',
        'lang',
    ];

    public function index()
{
    $logistiks = JalurDistribusiLogistik::with(['district', 'village'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('jalur_distribusi_logistik.index', compact('logistiks'));
}

    /**
     * Relasi ke tabel districts
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Relasi ke tabel villages
     */
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    
}
