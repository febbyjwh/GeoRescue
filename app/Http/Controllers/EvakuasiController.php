<?php

namespace App\Http\Controllers;

use App\Models\JalurEvakuasi;

use Illuminate\Http\Request;

class EvakuasiController extends Controller
{
    public function index()
    {
        $jalurs = JalurEvakuasi::orderBy('created_at', 'desc')->get();
        return view('admin.jalur_evakuasi.index', compact('jalurs'));
    }

    public function create()
    {
        return view('admin.jalur_evakuasi.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_jalur' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'geojson' => 'required|json',
        ]);

        JalurEvakuasi::create([
            'nama_jalur' => $request->nama_jalur,
            'deskripsi' => $request->deskripsi,
            'geojson' => $request->geojson,
        ]);

        return redirect()->back()->with('success', 'Jalur evakuasi berhasil dibuat!');
    }

    public function geojson()
    {
        $jalurs = JalurEvakuasi::all();

        return response()->json([
            "type" => "FeatureCollection",
            "features" => $jalurs->map(function ($jalur) {
                $geo = json_decode($jalur->geojson, true);

                return [
                    "type" => "Feature",
                    "geometry" => $geo['features'][0]['geometry'],
                    "properties" => [
                        "id" => $jalur->id,
                        "Nama" => $jalur->nama_jalur,
                        "Deskripsi" => $jalur->deskripsi,
                    ]
                ];
            })
        ]);
    }

    public function geojsonById($id)
    {
        $jalur = JalurEvakuasi::findOrFail($id);
        $geo = json_decode($jalur->geojson, true);

        return response()->json([
            'type' => 'Feature',
            'geometry' => $geo['features'][0]['geometry'],
            'properties' => [
                'id' => $jalur->id,
                'Nama' => $jalur->nama_jalur,
                'Deskripsi' => $jalur->deskripsi,
            ]
        ]);
    }

    public function edit($id)
    {
        return redirect()->route('mitigasi.index', [
            'form' => 'jalur',
            'edit_jalur' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jalur' => 'required',
            'geojson' => 'required|json'
        ]);

        JalurEvakuasi::findOrFail($id)->update([
            'nama_jalur' => $request->nama_jalur,
            'deskripsi' => $request->deskripsi,
            'geojson' => $request->geojson
        ]);

        return redirect()->back()->with('success', 'Jalur berhasil diupdate');
    }

    public function destroy($id)
    {
        $jalur = JalurEvakuasi::findOrFail($id);

        $jalur->delete();

        return redirect()
            ->route('jalur_evakuasi.index')
            ->with('success', 'Jalur evakuasi berhasil dihapus');
    }
}
