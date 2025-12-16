<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FasilitasVital;

class FasilitasVitalController extends Controller
{
    public function index()
    {
        $fasilitas = FasilitasVital::all();
        return view('Admin.FasilitasVital.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('Admin.FasilitasVital.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'jenis_fasilitas' => 'required',
            'alamat' => 'nullable',
            'desa' => 'required',
            'kecamatan' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:Beroperasi,Tidak Tersedia',
        ]);

        FasilitasVital::create($request->all());

        return redirect()->route('fasilitasvital.index')
            ->with('success', 'Fasilitas vital berhasil ditambahkan');
    }

    public function edit($id)
    {
        $fasilitas = FasilitasVital::findOrFail($id);
        return view('Admin.FasilitasVital.edit', compact('fasilitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_fasilitas' => 'required',
            'jenis_fasilitas' => 'required',
            'alamat' => 'nullable',
            'desa' => 'required',
            'kecamatan' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:Beroperasi,Tidak Tersedia',
        ]);

        $fasilitas = FasilitasVital::findOrFail($id);
        $fasilitas->update($request->all());

        return redirect()->route('fasilitasvital.index')
            ->with('success', 'Fasilitas vital berhasil diperbarui');
    }

    public function destroy($id)
    {
        $fasilitas = FasilitasVital::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('fasilitasvital.index')
            ->with('success', 'Fasilitas vital berhasil dihapus');
    }
}
