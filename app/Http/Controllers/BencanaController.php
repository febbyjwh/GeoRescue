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
        $bencanas = Bencana::with('district', 'village')->get()->map(function ($bn) {
            return [
                'id' => $bn->id,
                'kecamatan_id' => $bn->kecamatan_id,
                'desa_id' => $bn->desa_id,
                'nama_kecamatan' => $bn->district->name ?? '-',
                'nama_desa' => $bn->village->name ?? '-',
                'nama_bencana' => $bn->nama_bencana,
                'tingkat_kerawanan' => $bn->tingkat_kerawanan,
                'lang' => $bn->lang,
                'lat' => $bn->lat,
            ];
        });

        return response()->json(['data' => $bencanas]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'nama_bencana' => 'required|string|max:255',
            'tingkat_kerawanan' => 'required|string|max:100',
            'lang' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        Bencana::create($request->all());

        return response()->json(['message' => 'Bencana created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $bencanas = Bencana::findOrFail($id);
        $bencanas->update($request->all());
        return response()->json(['message' => 'Bencana updated successfully.']);
    }
}
