<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\PoskoBencana;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function bencana()
    {
        return response()->json([
            'data' => Bencana::with(['district', 'village'])->get()->map(function ($bn) {
                return [
                    'id' => $bn->id,
                    'kecamatan_id' => $bn->kecamatan_id,
                    'desa_id' => $bn->desa_id,
                    'nama_bencana' => $bn->nama_bencana,
                    'tingkat_kerawanan' => $bn->tingkat_kerawanan,
                    'lat' => $bn->lat,
                    'lang' => $bn->lang,
                    'nama_kecamatan' => $bn->district->name ?? '-',
                    'nama_desa' => $bn->village->name ?? '-',
                ];
            })
        ]);
    }

    public function Posko()
    {
        $poskos = PoskoBencana::with(['district', 'village'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'district_id' => $p->kecamatan_id,
                'village_id' => $p->desa_id,
                'nama_posko' => $p->nama_posko,
                'jenis_posko' => $p->jenis_posko,
                'status_posko' => $p->status_posko,
                'longitude' => $p->longitude,
                'latitude' => $p->latitude,
                'nama_kecamatan' => $p->district->name ?? '-',
                'nama_desa' => $p->village->name ?? '-',
            ];
        });

        return response()->json(['data' => $poskos]);
    }
}