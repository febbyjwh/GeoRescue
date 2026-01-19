<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\PoskoBencana;
use App\Models\FasilitasVital;
use App\Models\JalurDistribusiLogistik;

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
                    'jenis_bencana' => $bn->jenis_bencana,  // 'banjir', 'gempa', 'longsor'
                    'tingkat_kerawanan' => ucfirst($bn->tingkat_kerawanan),  
                    'status' => ucfirst($bn->status),  // Aktif, Penanganan, Selesai
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

        return response()->json(['data' => $fasilitas]);
    }

    public function logistik()
    {
        $logistiks = JalurDistribusiLogistik::with(['district', 'village'])->get()->map(function ($lg) {
            return [
                'id' => $lg->id,
                'nama_lokasi' => $lg->nama_lokasi,
                'kecamatan_id' => $lg->district_id,
                'desa_id' => $lg->village_id,
                'nama_kecamatan' => $lg->district->name ?? '-',
                'nama_desa' => $lg->village->name ?? '-',
                'jenis_logistik' => $lg->jenis_logistik,
                'jumlah' => $lg->jumlah,
                'satuan' => $lg->satuan,
                'status' => $lg->status,
                'lat' => $lg->lat,
                'lng' => $lg->lng,
            ];
        });

        return response()->json(['data' => $logistiks]);
    }
}
