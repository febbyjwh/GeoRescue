<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JalurDistribusiLogistik;
use Illuminate\Http\Request;

class JalurDistribusiLogistikController extends Controller
{

    public function index()
    {
        $logistiks = JalurDistribusiLogistik::with(['district', 'village'])->paginate(10);
        return view('admin.jalur_distribusi_logistik.index', compact('logistiks'));
    }


    public function getLogistik()
    {
        $logistiks = JalurDistribusiLogistik::with('district', 'village')->get()->map(function ($lg) {
            return [
                'id'              => $lg->id,
                'nama_lokasi'     => $lg->nama_lokasi,
                'kecamatan_id'    => $lg->district_id,
                'desa_id'         => $lg->village_id,
                'nama_kecamatan'  => $lg->district->name ?? '-',
                'nama_desa'       => $lg->village->name ?? '-',
                'jenis_logistik'  => $lg->jenis_logistik,
                'jumlah'          => $lg->jumlah,
                'logistik_satuan' => $lg->logistik_satuan,
                'logistik_status' => $lg->logistik_status,
                'lat'             => $lg->logistik_lat,
                'lng'             => $lg->logistik_lng,
            ];
        });

        return response()->json(['data' => $logistiks]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id'     => 'required|exists:districts,id',
            'village_id'      => 'required|exists:villages,id',
            'nama_lokasi'     => 'required|string|max:255',
            'jenis_logistik'  => 'required|string|max:255',
            'jumlah'          => 'required|numeric',
            'logistik_satuan' => 'required|string|max:100',
            'logistik_status' => 'required|string|max:100',
            'logistik_lat'    => 'required|numeric',
            'logistik_lng'    => 'required|numeric',
        ]);

        $validated['logistik_lat']  = (float) $validated['logistik_lat'];
        $validated['logistik_lng'] = (float) $validated['logistik_lng'];

        $logistik = JalurDistribusiLogistik::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logistik created successfully.',
                'data'    => [
                    'id'              => $logistik->id,
                    'district_id'     => $logistik->district_id,
                    'village_id'      => $logistik->village_id,
                    'nama_lokasi'     => $logistik->nama_lokasi,
                    'jenis_logistik'  => $logistik->jenis_logistik,
                    'jumlah'          => $logistik->jumlah,
                    'logistik_satuan' => $logistik->logistik_satuan,
                    'logistik_status' => $logistik->logistik_status,
                    'logistik_lat'    => $logistik->logistik_lat,
                    'logistik_lng'    => $logistik->logistik_lng,
                ]
            ], 201);
        }
        return redirect()
            ->route('jalur_distribusi_logistik.index')
            ->with('success', 'Data logistik berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'district_id'     => 'required|exists:districts,id',
            'village_id'      => 'required|exists:villages,id',
            'nama_lokasi'     => 'required|string|max:255',
            'jenis_logistik'  => 'required|string|max:255',
            'jumlah'          => 'required|numeric',
            'logistik_satuan' => 'required|string|max:100',
            'logistik_status' => 'required|string|max:100',
            'logistik_lat'    => 'required|numeric',
            'logistik_lng'    => 'required|numeric',
        ]);

        $validated['logistik_lat'] = (float) $validated['logistik_lat'];
        $validated['logistik_lng'] = (float) $validated['logistik_lng'];

        $logistik = JalurDistribusiLogistik::findOrFail($id);
        $logistik->update($validated);

        return response()->json([
            'message' => 'Data logistik berhasil diperbarui'
        ]);
    }
    

    public function edit($id)
    {
        $logistik = JalurDistribusiLogistik::with('district', 'village')->findOrFail($id);

        return response()->json([
            'id'              => $logistik->id,
            'district_id'     => $logistik->district_id,
            'village_id'      => $logistik->village_id,
            'nama_kecamatan'  => $logistik->district->name ?? '-',
            'nama_desa'       => $logistik->village->name ?? '-',
            'nama_lokasi'     => $logistik->nama_lokasi,
            'jenis_logistik'  => $logistik->jenis_logistik,
            'jumlah'          => $logistik->jumlah,
            'logistik_satuan' => $logistik->logistik_satuan,
            'logistik_status' => $logistik->logistik_status,
            'logistik_lng'    => $logistik->logistik_lng,
            'logistik_lat'    => $logistik->logistik_lat,
        ]);
    }

    public function destroy($id)
    {
        $logistik = JalurDistribusiLogistik::findOrFail($id);
        $logistik->delete();

        return response()->json([
            'message' => 'Data logistik berhasil dihapus'
        ]);
    }
}
