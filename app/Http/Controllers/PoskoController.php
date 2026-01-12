<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoskoBencana;

class PoskoController extends Controller
{
    public function index()
    {
        $poskos = PoskoBencana::with(['district', 'village'])->paginate(10);
        return view('admin.posko.index', compact('poskos'));
    }

    public function getPosko()
    {
        $poskos = PoskoBencana::with(['district', 'village'])->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'kecamatan_id' => $p->kecamatan_id,
                'desa_id' => $p->desa_id,
                'nama_kecamatan' => $p->district->name ?? '-',
                'nama_desa' => $p->village->name ?? '-',
                'nama_posko' => $p->nama_posko,
                'jenis_posko' => $p->jenis_posko,
                'status_posko' => $p->status_posko,
                'latitude' => $p->latitude,
                'longitude' => $p->longitude,
            ];
        });

        return response()->json(['data' => $poskos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_posko' => 'required|string|max:255',
            'jenis_posko' => 'required|string|max:100',
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_posko' => 'required|in:Aktif,Penuh,Tutup',
        ]);

        PoskoBencana::create($request->only([
            'nama_posko',
            'jenis_posko',
            'kecamatan_id',
            'desa_id',
            'latitude',
            'longitude',
            'status_posko',
        ]));

        return response()->json(['message' => 'Posko berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $posko = PoskoBencana::with(['district', 'village'])->findOrFail($id);

        return response()->json([
            'id' => $posko->id,
            'kecamatan_id' => $posko->kecamatan_id,
            'desa_id' => $posko->desa_id,
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
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_posko' => 'required|in:Aktif,Penuh,Tutup',
        ]);

        $posko = PoskoBencana::findOrFail($id);
        $posko->update($request->only([
            'nama_posko',
            'jenis_posko',
            'kecamatan_id',
            'desa_id',
            'latitude',
            'longitude',
            'status_posko',
        ]));

        return response()->json(['message' => 'Posko berhasil diperbarui']);
    }

    public function destroy($id)
    {
        PoskoBencana::findOrFail($id)->delete();
        return response()->json(['message' => 'Posko berhasil dihapus']);
    }
}
