<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JalurDistribusiLogistik;
use App\Models\Village;

class JalurDistribusiLogistikController extends Controller
{
    /**
     * Halaman index data logistik
     */
    public function index()
    {
        $logistiks = JalurDistribusiLogistik::with('district', 'village')->get();
        return view('Admin.logistik.index', compact('logistiks'));
    }

    /**
     * Halaman form tambah logistik
     */
    public function create()
    {
        return view('Admin.logistik.create');
    }

    /**
     * ENDPOINT JSON UNTUK LEAFLET
     * Pola sama dengan getBencana()
     */
    public function getLogistik()
    {
        $logistiks = JalurDistribusiLogistik::with('district', 'village')
            ->get()
            ->map(function ($lg) {

                return [
                    'id' => $lg->id,

                    'district_id' => $lg->district_id,
                    'village_id' => $lg->village_id,

                    'nama_kecamatan' => $lg->district->name ?? '-',
                    'nama_desa' => $lg->village->name ?? '-',

                    'nama_lokasi' => $lg->nama_lokasi,
                    'jenis_logistik' => $lg->jenis_logistik,
                    'jumlah' => $lg->jumlah,
                    'satuan' => $lg->satuan,
                    'status' => $lg->status,

                    // ⬇️ TITIK MAP DIAMBIL DARI DESA
                    'lat' => $lg->village->latitude ?? null,
                    'lng' => $lg->village->longitude ?? null,
                ];
            });

        return response()->json(['data' => $logistiks]);
    }

    /**
     * Simpan data logistik
     * TANPA input latitude & longitude
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi'    => 'required|string',
            'district_id'    => 'required|exists:districts,id',
            'village_id'     => 'required|exists:villages,id',
            'jenis_logistik' => 'required|string',
            'jumlah'         => 'required|numeric',
            'satuan'         => 'required|string',
            'status'         => 'required|string',
        ]);

        JalurDistribusiLogistik::create([
            'nama_lokasi'    => $request->nama_lokasi,
            'district_id'    => $request->district_id,
            'village_id'     => $request->village_id,
            'jenis_logistik' => $request->jenis_logistik,
            'jumlah'         => $request->jumlah,
            'satuan'         => $request->satuan,
            'status'         => $request->status,
        ]);

        return redirect()
            ->route('logistik.index')
            ->with('success', 'Data logistik berhasil ditambahkan');
    }
}
