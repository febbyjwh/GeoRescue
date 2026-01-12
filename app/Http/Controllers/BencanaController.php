<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use Illuminate\Http\Request;

class BencanaController extends Controller
{
    public function index()
    {
        $bencanas = Bencana::with(['district', 'village'])->paginate(10);
        return view('admin.bencana.index', compact('bencanas'));
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
                'jenis_bencana' => $bn->jenis_bencana,
                'tingkat_kerawanan' => $bn->tingkat_kerawanan,
                'status' => $bn->status,
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
            'jenis_bencana' => 'required|string|max:255',
            'tingkat_kerawanan' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'lang' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        Bencana::create($request->all());

        return response()->json(['message' => 'Bencana created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'jenis_bencana' => 'required|string|max:255',
            'tingkat_kerawanan' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'lang' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        $bencana = Bencana::findOrFail($id);
        $bencana->update($request->all());

        return response()->json([
            'message' => 'Data bencana berhasil diperbarui'
        ]);
    }

    public function edit($id)
    {
        $bencana = Bencana::with('district', 'village')->findOrFail($id);

        return response()->json([
            'id' => $bencana->id,
            'kecamatan_id' => $bencana->kecamatan_id,
            'desa_id' => $bencana->desa_id,
            'jenis_bencana' => $bencana->jenis_bencana,
            'tingkat_kerawanan' => $bencana->tingkat_kerawanan,
            'status' => $bencana->status,
            'lang' => $bencana->lang,
            'lat' => $bencana->lat,
        ]);
    }

    public function destroy($id)
    {
        $bencana = Bencana::findOrFail($id);
        $bencana->delete();

        return response()->json([
            'message' => 'Data bencana berhasil dihapus'
        ]);
    }
}
