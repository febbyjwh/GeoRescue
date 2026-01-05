<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoskoBencana;

class PoskoController extends Controller
{
    public function index()
    {
        return view('admin.posko.index');
    }

    public function getPosko()
    {
        $poskos = PoskoBencana::with(['district', 'village'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'district_id' => $p->district_id, 
                'village_id' => $p->village_id,   
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

    public function store(Request $request)
    {
        $request->validate([
            'nama_posko' => 'required|string|max:255',
            'jenis_posko' => 'required|string|max:100',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_posko' => 'required|in:Aktif,Penuh,Tutup',
        ]);

        PoskoBencana::create($request->all());

        return response()->json(['message' => 'Posko berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $posko = PoskoBencana::with(['district', 'village'])->findOrFail($id);

        return response()->json([
            'id' => $posko->id,
            'district_id' => $posko->district_id,
            'village_id' => $posko->village_id,
            'nama_posko' => $posko->nama_posko,
            'jenis_posko' => $posko->jenis_posko,
            'status_posko' => $posko->status_posko,
            'latitude' => $posko->latitude,
            'longitude' => $posko->longitude,
            'nama_kecamatan' => $posko->district->name ?? '-',
            'nama_desa' => $posko->village->name ?? '-',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_posko' => 'required|string|max:255',
            'jenis_posko' => 'required|string|max:100',
            'district_id' => 'required|exists:districts,id',
            'village_id' => 'required|exists:villages,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_posko' => 'required|in:Aktif,Penuh,Tutup',
        ]);

        $posko = PoskoBencana::findOrFail($id);
        $posko->update($request->all());

        return response()->json(['message' => 'Posko berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $posko = PoskoBencana::findOrFail($id);
        $posko->delete();

        return response()->json(['message' => 'Posko berhasil dihapus']);
    }
}