<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Village;
use App\Models\Bencana;
use App\Models\PoskoBencana;
use Illuminate\Http\Request;

class MitigasiController extends Controller
{
    public function index()
    {
        return view('Admin.mitigasi.index', [
            'districts' => $this->getDistricts(),
            'villages' => $this->getVillages(),
            'bencanaSummary' => $this->getBencanaSummary(),
            'poskoSummary' => $this->getPoskoSummary(),
        ]);
    }

    private function getDistricts()
    {
        return District::orderBy('name')->get();
    }

    private function getVillages()
    {
        return Village::select('id', 'name', 'district_id')
            ->orderBy('name')
            ->get()
            ->groupBy('district_id');
    }

    private function getBencanaSummary()
    {
        $data = Bencana::with('district')->get();

        // total titik
        $total = $data->count();

        // jumlah kecamatan & desa terdampak
        $kecamatan = $data->pluck('kecamatan_id')->unique()->count();
        $desa = $data->pluck('desa_id')->unique()->count();

        // jenis bencana
        $jenis = $data->groupBy('jenis_bencana')->map->count();

        // tingkat kerawanan
        $kerawanan = $data->groupBy('tingkat_kerawanan')->map->count();

        // status kejadian
        $status = $data->groupBy('status')->map->count();

        // wilayah paling terdampak (kecamatan)
        $topKecamatan = Bencana::select('kecamatan_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan_id')
            ->orderByDesc('total')
            ->with('district')
            ->first();

        return [
            'total' => $total,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'jenis' => $jenis,
            'kerawanan' => $kerawanan,
            'status' => $status,
            'wilayah_terdampak' => $topKecamatan
                ? [
                    'nama' => $topKecamatan->district->name ?? '-',
                    'total' => $topKecamatan->total,
                ]
                : null,
            'last_update' => $data->max('updated_at'),
            'radius' => '1.000 meter',
        ];
    }

    private function getPoskoSummary()
    {
        $data = PoskoBencana::with(['district', 'village'])->get();

        // total posko
        $total = $data->count();

        // status posko
        $status = $data->groupBy('status_posko')->map->count();

        // jenis posko
        $jenis = $data->groupBy('jenis_posko')->map->count();

        // sebaran wilayah
        $kecamatan = $data->pluck('kecamatan_id')->unique()->count();
        $desa = $data->pluck('desa_id')->unique()->count();

        // kecamatan dengan posko terbanyak
        $topKecamatan = PoskoBencana::select('kecamatan_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan_id')
            ->orderByDesc('total')
            ->with('district')
            ->first();

        return [
            'total' => $total,
            'status' => $status,
            'jenis' => $jenis,
            'kecamatan' => $kecamatan,
            'desa' => $desa,
            'wilayah_terbanyak' => $topKecamatan
                ? [
                    'nama' => $topKecamatan->district->name ?? '-',
                    'total' => $topKecamatan->total,
                ]
                : null,
            'last_update' => $data->max('updated_at'),
        ];
    }
}
