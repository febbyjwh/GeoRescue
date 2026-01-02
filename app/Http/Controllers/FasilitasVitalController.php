<?php

namespace App\Http\Controllers;

use App\Models\FasilitasVital;
use Illuminate\Http\Request;

class FasilitasVitalController extends Controller
{
    public function index()
    {
        $fasilitas = FasilitasVital::with(['district', 'village'])->get();
        return view('admin.FasilitasVital.index', compact('fasilitas'));
    }

    public function getFasilitas()
    {
        $fasilitas = FasilitasVital::with('district', 'village')->get()->map(function ($f) {
            return [
                'id' => $f->id,
                'kecamatan_id' => $f->kecamatan_id,
                'desa_id' => $f->desa_id,
                'nama_kecamatan' => $f->district->name ?? '-',
                'nama_desa' => $f->village->name ?? '-',
                'nama_fasilitas' => $f->nama_fasilitas,
                'jenis_fasilitas' => $f->jenis_fasilitas,
                'alamat' => $f->alamat,
                'status' => $f->status,
                'latitude' => $f->latitude,
                'longitude' => $f->longitude,
            ];
        });

        return response()->json(['data' => $fasilitas]);
    }

    public function create()
    {
        return view('admin.FasilitasVital.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'nama_fasilitas' => 'required|string|max:255',
            'jenis_fasilitas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Beroperasi,Tidak Tersedia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        FasilitasVital::create($request->all());

        return response()->json(['message' => 'Fasilitas vital berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $fasilitas = FasilitasVital::with('district', 'village')->findOrFail($id);

        return response()->json([
            'id' => $fasilitas->id,
            'kecamatan_id' => $fasilitas->kecamatan_id,
            'desa_id' => $fasilitas->desa_id,
            'nama_fasilitas' => $fasilitas->nama_fasilitas,
            'jenis_fasilitas' => $fasilitas->jenis_fasilitas,
            'alamat' => $fasilitas->alamat,
            'status' => $fasilitas->status,
            'latitude' => $fasilitas->latitude,
            'longitude' => $fasilitas->longitude,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'nama_fasilitas' => 'required|string|max:255',
            'jenis_fasilitas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'status' => 'required|in:Beroperasi,Tidak Tersedia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $fasilitas = FasilitasVital::findOrFail($id);
        $fasilitas->update($request->all());

        return response()->json([
            'message' => 'Fasilitas vital berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $fasilitas = FasilitasVital::findOrFail($id);
        $fasilitas->delete();

        return response()->json([
            'message' => 'Fasilitas vital berhasil dihapus'
        ]);
    }
}