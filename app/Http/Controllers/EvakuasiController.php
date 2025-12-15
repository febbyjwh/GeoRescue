<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvakuasiController extends Controller
{
    public function index(){
        return view ('admin.jalur_evakuasi.index');
    }

    public function create()
    {
        return view ('admin.jalur_evakuasi.create');
    }
    
    public function store()
    {
        $request->validate([
            'nama_jalur' => 'required|string',
            'geojson' => 'required|string'
        ]);

        JalurEvakuasi::create([
            'nama_jalur' => $request->nama_jalur,
            'geojson' => $request->geojson,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.jalur_evakuasi.index')->with('success', 'Jalur berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        return view ('admin.jalur_evakuasi.edit');
    }
    
    public function update($id)
    {
        return view ('admin.jalur_evakuasi.edit');
    }

    public function destroy()
    {
        return view ('admin.jalur_evakuasi.index');
    }
}