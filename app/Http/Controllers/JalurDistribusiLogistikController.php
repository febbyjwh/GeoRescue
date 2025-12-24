<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalurDistribusiLogistik;

class JalurDistribusiLogistikController extends Controller
{
    public function index()
    {
        $jalur = JalurDistribusiLogistik::all();
        return view('Admin.jalur_distribusi_logistik.index', compact('jalur'));
    }

    public function create()
    {
        return view('Admin.jalur_distribusi_logistik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jalur' => 'required',
            'asal_logistik' => 'required',
            'asal_latitude' => 'nullable|numeric',
            'asal_longitude' => 'nullable|numeric',
            'tujuan_distribusi' => 'required',
            'tujuan_latitude' => 'nullable|numeric',
            'tujuan_longitude' => 'nullable|numeric',
            'status_jalur' => 'required|in:aktif,terhambat,ditutup',
        ]);

        JalurDistribusiLogistik::create($request->all());

        return redirect()->route('jalur_distribusi_logistik.index')
            ->with('success', 'Jalur distribusi logistik berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jalur = JalurDistribusiLogistik::findOrFail($id);
        return view('Admin.jalur_distribusi_logistik.edit', compact('jalur'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jalur' => 'required',
            'asal_logistik' => 'required',
            'asal_latitude' => 'nullable|numeric',
            'asal_longitude' => 'nullable|numeric',
            'tujuan_distribusi' => 'required',
            'tujuan_latitude' => 'nullable|numeric',
            'tujuan_longitude' => 'nullable|numeric',
            'status_jalur' => 'required|in:aktif,terhambat,ditutup',
        ]);

        $jalur = JalurDistribusiLogistik::findOrFail($id);
        $jalur->update($request->all());

        return redirect()->route('jalur_distribusi_logistik.index')
            ->with('success', 'Jalur distribusi logistik berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jalur = JalurDistribusiLogistik::findOrFail($id);
        $jalur->delete();

        return redirect()->route('jalur_distribusi_logistik.index')
            ->with('success', 'Jalur distribusi logistik berhasil dihapus');
    }

    public function show($id)
    {
        $jalur = JalurDistribusiLogistik::findOrFail($id);
        return view('Admin.jalur_distribusi_logistik.show', compact('jalur'));
    }
}
