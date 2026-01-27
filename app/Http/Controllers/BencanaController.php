<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use Illuminate\Http\Request;

class BencanaController extends Controller
{
    /**
     * Mapping satuan berdasarkan jenis bencana
     */
    private array $satuanMap = [
        'banjir' => ['cm', 'm'],
        'gempa'  => ['sr', 'km', 'mmi'],
        'longsor'=> ['m', 'm3', 'ha'],
    ];

    public function index()
    {
        $bencanas = Bencana::with(['district', 'village'])->paginate(10);
        return view('admin.bencana.index', compact('bencanas'));
    }

    public function getBencana()
    {
        $bencanas = Bencana::with(['district', 'village'])->get()->map(function ($bn) {
            return [
                'id' => $bn->id,
                'kecamatan_id' => $bn->kecamatan_id,
                'desa_id' => $bn->desa_id,
                'nama_kecamatan' => $bn->district->name ?? '-',
                'nama_desa' => $bn->village->name ?? '-',
                'jenis_bencana' => $bn->jenis_bencana,
                'tingkat_kerawanan' => $bn->tingkat_kerawanan,
                'nilai' => $bn->nilai,
                'satuan' => $bn->satuan,
                'status' => $bn->status,
                'lang' => $bn->lang,
                'lat' => $bn->lat,
            ];
        });

        return response()->json(['data' => $bencanas]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'jenis_bencana' => 'required|in:banjir,gempa,longsor',
            'tingkat_kerawanan' => 'required|in:rendah,sedang,tinggi',
            'nilai' => 'required|numeric|min:0', 
            'satuan' => 'required|string',
            'status' => 'required|in:aktif,penanganan,selesai',
            'lang' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        // validasi satuan sesuai jenis bencana
        if (!in_array($request->satuan, $this->satuanMap[$request->jenis_bencana])) {
            return response()->json([
                'message' => 'Satuan tidak sesuai dengan jenis bencana'
            ], 422);
        }

        Bencana::create($request->all());

        return response()->json([
            'message' => 'Bencana created successfully'
        ]);
    }

    public function edit($id)
    {
        $bencana = Bencana::with(['district', 'village'])->findOrFail($id);

        return response()->json([
            'id' => $bencana->id,
            'kecamatan_id' => $bencana->kecamatan_id,
            'desa_id' => $bencana->desa_id,
            'jenis_bencana' => $bencana->jenis_bencana,
            'tingkat_kerawanan' => $bencana->tingkat_kerawanan,
            'nilai' => $bencana->nilai,
            'satuan' => $bencana->satuan,   //
            'status' => $bencana->status,
            'lang' => $bencana->lang,
            'lat' => $bencana->lat,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:districts,id',
            'desa_id' => 'required|exists:villages,id',
            'jenis_bencana' => 'required|in:banjir,gempa,longsor',
            'tingkat_kerawanan' => 'required|in:rendah,sedang,tinggi',
            'nilai' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'status' => 'required|in:aktif,penanganan,selesai',
            'lang' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        if (!in_array($request->satuan, $this->satuanMap[$request->jenis_bencana])) {
            return response()->json([
                'message' => 'Satuan tidak sesuai dengan jenis bencana'
            ], 422);
        }

        $bencana = Bencana::findOrFail($id);
        $bencana->update($request->all());

        return response()->json([
            'message' => 'Data bencana berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        Bencana::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Data bencana berhasil dihapus'
        ]);
    }
}