<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PoskoBencana;

class PoskoController extends Controller
{
    public function index()
    {
        $poskos = PoskoBencana::all();
        return view('Admin.Posko.index', compact('poskos'));

    }

     public function create()
    {
        return view('admin.mitigasi.partials.posko');
    }

   public function store(Request $request)
    {
    $request->validate([
        'nama_posko' => 'required',
        'jenis_posko' => 'required',
        'alamat_posko' => 'nullable',
        'nama_desa' => 'required',
        'kecamatan' => 'required',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'status_posko' => 'required|in:Aktif,Penuh,Tutup',
    ]);

    PoskoBencana::create($request->all());

    return redirect()->route('posko.index')->with('success', 'Posko berhasil ditambahkan');
    }

    public function edit($id)
    {
        $posko = PoskoBencana::findOrFail($id);
        return view('Admin.Posko.edit', compact('posko'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_posko' => 'required',
            'jenis_posko' => 'required',
            'alamat_posko' => 'nullable',
            'nama_desa' => 'required',
            'kecamatan' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_posko' => 'required|in:Aktif,Penuh,Tutup',
        ]);

        $posko = PoskoBencana::findOrFail($id);
        $posko->update($request->all());

        return redirect()->route('posko.index')->with('success', 'Posko berhasil diperbarui');
    }

    public function destroy($id)
    {
        $posko = PoskoBencana::findOrFail($id);
        $posko->delete();

        return redirect()->route('posko.index')->with('success', 'Posko berhasil dihapus');
    }

}
