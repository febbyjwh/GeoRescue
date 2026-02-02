<?php

namespace App\Http\Controllers;

use App\Models\FasilitasVital;
use Illuminate\Http\Request;

class FasilitasVitalController extends Controller
{
    public function index()
    {
        $fasilitas = FasilitasVital::with(['district', 'village'])->paginate(10);
        return view('admin.FasilitasVital.index', compact('fasilitas'));
    }

    public function getFasilitas()
    {
        $fasilitas = FasilitasVital::with('district', 'village')->get()->map(function ($f) {
            return [
                'id'               => $f->id,
                'kecamatan_id'     => $f->kecamatan_id,
                'desa_id'          => $f->desa_id,
                'nama_kecamatan'   => $f->district->name ?? '-',
                'nama_desa'        => $f->village->name ?? '-',
                'nama_fasilitas'   => $f->nama_fasilitas,
                'jenis_fasilitas'  => $f->jenis_fasilitas,
                'alamat'           => $f->alamat,
                'fasilitas_status' => $f->fasilitas_status,
                'fasilitas_lat'    => $f->fasilitas_lat,
                'fasilitas_lng'    => $f->fasilitas_lng,
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
            'kecamatan_id'     => 'required|exists:districts,id',
            'desa_id'          => 'required|exists:villages,id',
            'nama_fasilitas'   => 'required|string|max:255',
            'jenis_fasilitas'  => 'required|string|max:100',
            'alamat'           => 'nullable|string',
            'fasilitas_status' => 'required|in:Beroperasi,Tidak Tersedia',
            'fasilitas_lat'    => 'required|numeric',
            'fasilitas_lng'    => 'required|numeric',
        ]);

        FasilitasVital::create($request->all());

        return response()->json(['message' => 'Fasilitas vital berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $fasilitas = FasilitasVital::with('district', 'village')->findOrFail($id);

        return response()->json([
            'id'               => $fasilitas->id,
            'kecamatan_id'     => $fasilitas->kecamatan_id,
            'desa_id'          => $fasilitas->desa_id,
            'nama_fasilitas'   => $fasilitas->nama_fasilitas,
            'jenis_fasilitas'  => $fasilitas->jenis_fasilitas,
            'alamat'           => $fasilitas->alamat,
            'fasilitas_status' => $fasilitas->fasilitas_status,
            'fasilitas_lat'    => $fasilitas->fasilitas_lat,
            'fasilitas_lng'    => $fasilitas->fasilitas_lng,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id'     => 'required|exists:districts,id',
            'desa_id'          => 'required|exists:villages,id',
            'nama_fasilitas'   => 'required|string|max:255',
            'jenis_fasilitas'  => 'required|string|max:100',
            'alamat'           => 'nullable|string',
            'fasilitas_status' => 'required|in:Beroperasi,Tidak Tersedia',
            'fasilitas_lat'    => 'required|numeric',
            'fasilitas_lng'    => 'required|numeric',
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