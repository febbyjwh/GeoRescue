<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\PoskoBencana;
use App\Models\FasilitasVital;

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

    public function posko()
    {
        $poskos = PoskoBencana::with(['district', 'village'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'district_id' => $p->kecamatan_id,
                'village_id' => $p->desa_id,
                'nama_posko' => $p->nama_posko,
                'jenis_posko' => $p->jenis_posko,
                'status_posko' => $p->status_posko,
                'latitude' => $p->latitude,
                'longitude' => $p->longitude,
                'nama_kecamatan' => $p->district->name ?? '-',
                'nama_desa' => $p->village->name ?? '-',
            ];
        });

        return response()->json(['data' => $poskos]);
    }

    public function fasilitas()
    {
        $fasilitas = FasilitasVital::with(['district', 'village'])->get()->map(function ($f) {
            return [
                'id' => $f->id,
                'kecamatan_id' => $f->kecamatan_id,
                'desa_id' => $f->desa_id,
                'nama_fasilitas' => $f->nama_fasilitas,
                'jenis_fasilitas' => $f->jenis_fasilitas,
                'alamat' => $f->alamat,
                'status' => $f->status,
                'latitude' => $f->latitude,
                'longitude' => $f->longitude,
                'nama_kecamatan' => $f->district->name ?? '-',
                'nama_desa' => $f->village->name ?? '-',
            ];
        });

        return response()->json([ 'data' => $fasilitas]);
    }
}