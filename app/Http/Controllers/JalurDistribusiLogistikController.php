<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalurDistribusiLogistik;
use App\Models\Village;


class JalurDistribusiLogistikController extends Controller
{

    public function index()
    {
        $logistiks = JalurDistribusiLogistik::all();
        return view('Admin.jalur_distribusi_logistik.index', compact('logistiks'));
    }


    public function getLogistik()
{
    $logistiks = JalurDistribusiLogistik::with('district', 'village')->get()->map(function ($lg) {
        return [
            'id' => $lg->id,
            'kecamatan_id' => $lg->district_id,
            'desa_id' => $lg->village_id,
            'nama_kecamatan' => $lg->district->name ?? '-',
            'nama_desa' => $lg->village->name ?? '-',
            'nama_lokasi' => $lg->nama_lokasi,
            'jenis_logistik' => $lg->jenis_logistik,
            'jumlah' => $lg->jumlah,
            'satuan' => $lg->satuan,
            'status' => $lg->status,
            'lang' => $lg->village->longitude ?? null,
            'lat' => $lg->village->latitude ?? null,
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

        // ✅ pastikan input lat/lang masuk
        'lat'            => 'required|numeric',
        'lang'           => 'required|numeric',
    ]);

    // ✅ normalize biar pasti numeric rapi
    $validated['lat']  = (float) $validated['lat'];
    $validated['lang'] = (float) $validated['lang'];

    $logistik = JalurDistribusiLogistik::create($validated);

    // ✅ Kalau request dari fetch/ajax → balikin JSON
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
                'lang'            => $logistik->lang,
            ]
        ], 201);
    }

    // ✅ Kalau submit form biasa → redirect
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

            // samakan response key seperti bencana
            'kecamatan_id' => $logistik->district_id,
            'desa_id'      => $logistik->village_id,

            'nama_lokasi'    => $logistik->nama_lokasi,
            'jenis_logistik' => $logistik->jenis_logistik,
            'jumlah'         => $logistik->jumlah,
            'satuan'         => $logistik->satuan,
            'status'         => $logistik->status,

            // kalau frontend butuh lat/lng seperti bencana:
            'lang' => $logistik->village->longitude ?? null,
            'lat'  => $logistik->village->latitude ?? null,
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

   public function villagesByDistrict($districtId)
    {
        $villages = Village::select('id','name')
            ->where('district_id', $districtId)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $villages]);
    }

    // public function geojson()
    // {
    //     $path = database_path('data/bandung_villages.geojson');

    //     if (!File::exists($path)) {
    //         return response()->json([
    //             'message' => 'File geojson tidak ditemukan'
    //         ], 404);
    //     }

    //     $geojson = json_decode(File::get($path), true);

    //     // OPTIONAL: kalau mau langsung return GEOJSON utuh
    //     return response()->json($geojson);
    // }


     
}
