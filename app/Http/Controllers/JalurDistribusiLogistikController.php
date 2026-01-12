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
                'id' => $lg->id,
                'nama_lokasi' => $lg->nama_lokasi,
                'kecamatan_id' => $lg->district_id,
                'desa_id' => $lg->village_id,
                'nama_kecamatan' => $lg->district->name ?? '-',
                'nama_desa' => $lg->village->name ?? '-',
                'jenis_logistik' => $lg->jenis_logistik,
                'jumlah' => $lg->jumlah,
                'satuan' => $lg->satuan,
                'status' => $lg->status,
                'lat' => $lg->lat,
                'lng' => $lg->lng,
            ];
        });

        return response()->json(['data' => $logistiks]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id'    => 'required|exists:districts,id',
            'village_id'     => 'required|exists:villages,id',
            'nama_lokasi'    => 'required|string|max:255',
            'jenis_logistik' => 'required|string|max:255',
            'jumlah'         => 'required|numeric',
            'satuan'         => 'required|string|max:100',
            'status'         => 'required|string|max:100',
            'lat'            => 'required|numeric',
            'lng'           => 'required|numeric',
        ]);

        $validated['lat']  = (float) $validated['lat'];
        $validated['lng'] = (float) $validated['lng'];

        $logistik = JalurDistribusiLogistik::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logistik created successfully.',
                'data'    => [
                    'id'             => $logistik->id,
                    'kecamatan_id'    => $logistik->district_id,
                    'desa_id'         => $logistik->village_id,
                    'nama_lokasi'     => $logistik->nama_lokasi,
                    'jenis_logistik'  => $logistik->jenis_logistik,
                    'jumlah'          => $logistik->jumlah,
                    'satuan'          => $logistik->satuan,
                    'status'          => $logistik->status,
                    'lat'             => $logistik->lat,
                    'lng'            => $logistik->lng,
                ]
            ], 201);
        }
        return redirect()
            ->route('jalur_distribusi_logistik.index')
            ->with('success', 'Data logistik berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan_id'   => 'required|exists:districts,id',
            'desa_id'        => 'required|exists:villages,id',

            'nama_lokasi'    => 'required|string|max:255',
            'jenis_logistik' => 'required|string|max:255',
            'jumlah'         => 'required|numeric',
            'satuan'         => 'required|string|max:100',
            'status'         => 'required|string|max:100',
        ]);

        $logistik = JalurDistribusiLogistik::findOrFail($id);

        $logistik->update([
            'district_id'    => $request->kecamatan_id,
            'village_id'     => $request->desa_id,

            'nama_lokasi'    => $request->nama_lokasi,
            'jenis_logistik' => $request->jenis_logistik,
            'jumlah'         => $request->jumlah,
            'satuan'         => $request->satuan,
            'status'         => $request->status,
        ]);

        return response()->json([
            'message' => 'Data logistik berhasil diperbarui'
        ]);
    }

    public function edit($id)
    {
        $logistik = JalurDistribusiLogistik::with('district', 'village')->findOrFail($id);

        return response()->json([
            'id' => $logistik->id,
            'kecamatan_id' => $logistik->district_id,
            'desa_id'      => $logistik->village_id,
            'nama_kecamatan' => $logistik->district->name ?? '-',
            'nama_desa' => $logistik->village->name ?? '-',
            'nama_lokasi'    => $logistik->nama_lokasi,
            'jenis_logistik' => $logistik->jenis_logistik,
            'jumlah'         => $logistik->jumlah,
            'satuan'         => $logistik->satuan,
            'status'         => $logistik->status,
            'lng' => $logistik->lng,
            'lat' => $logistik->lat,
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
