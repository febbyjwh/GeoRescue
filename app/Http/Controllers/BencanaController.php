<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use Illuminate\Http\Request;

class BencanaController extends Controller
{
    public function index()
    {
        return view('admin.bencana.index');
    }

    public function getBencana()
    {
        $bencanas = Bencana::get()->map(function ($bn) {
            return [
                'id' => $bn->id,
                "kecamatan" => $bn->kecamatan,
                "desa" => $bn->desa,
                "nama_bencana" => $bn->nama_bencana,
                "tingkat_kerawanan" => $bn->tingkat_kerawanan,
                "lang" => $bn->lang,
                "lat" => $bn->lat
            ];
        });

        return response()->json(['data' => $bencanas]);
    }
}