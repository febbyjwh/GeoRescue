<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Village;
use App\Models\Bencana;
use App\Models\PoskoBencana;
use App\Models\FasilitasVital;
use App\Models\JalurDistribusiLogistik;
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
            'fasilitasSummary' => $this->getFasilitasSummary(),
            'logistikSummary' => $this->getLogistikSummary(),
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
        $total = $data->count();
        $kecamatan = $data->pluck('kecamatan_id')->unique()->count();
        $desa = $data->pluck('desa_id')->unique()->count();
        $jenis = $data->groupBy('jenis_bencana')->map->count();
        $kerawanan = $data->groupBy('tingkat_kerawanan')->map->count();
        $status = $data->groupBy('status')->map->count();
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
        $data = \App\Models\PoskoBencana::with(['district', 'village'])->get();
        $total = $data->count();
        $statusRaw = $data->groupBy('status_posko')->map->count()->toArray();
        $status = [
            'Aktif' => $statusRaw['Aktif'] ?? 0,
            'Penuh' => $statusRaw['Penuh'] ?? 0,
            'Tutup' => $statusRaw['Tutup'] ?? 0,
        ];
        $jenisRaw = $data->groupBy('jenis_posko')->map->count()->toArray();
        $kecamatan = $data->pluck('kecamatan_id')->unique()->count();
        $desa = $data->pluck('desa_id')->unique()->count();
        $topKecamatan = PoskoBencana::select('kecamatan_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan_id')
            ->orderByDesc('total')
            ->with('district')
            ->first();

        return [
            'total' => $total,
            'status' => $status,
            'jenis' => $jenisRaw,
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

    private function getFasilitasSummary()
    {
        $data = FasilitasVital::with(['district', 'village'])->get();
        $total = $data->count();
        $jenis = $data->groupBy('jenis_fasilitas')->map->count();
        $statusRaw = $data->groupBy('status')->map->count()->toArray();
        $status = [
            'Beroperasi' => $statusRaw['Beroperasi'] ?? 0,
            'Tidak Tersedia' => $statusRaw['Tidak Tersedia'] ?? 0,
        ];
        $kecamatan = $data->pluck('kecamatan_id')->unique()->count();
        $desa = $data->pluck('desa_id')->unique()->count();
        $topKecamatan = FasilitasVital::select('kecamatan_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('kecamatan_id')
            ->orderByDesc('total')
            ->with('district')
            ->first();

        return [
            'total' => $total,
            'jenis' => $jenis,
            'status' => $status,
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

    private function getLogistikSummary()
    {
        $data = JalurDistribusiLogistik::all();

        $jenis = $data->groupBy('jenis_logistik')->map(function ($items) {
            return $items->sum('jumlah');
        });

        $jenisTerbanyak = $jenis->sortDesc()->keys()->first();

        $lastUpdate = $data->max('updated_at');

        return [
            'total_lokasi' => $data->count(),
            'total_stok' => $data->sum('jumlah'),
            'kecamatan' => $data->pluck('district_id')->unique()->count(),
            'desa' => $data->pluck('village_id')->unique()->count(),
            'jenis' => $jenis,
            'jenis_terbanyak' => $jenisTerbanyak,
            'last_update' => $lastUpdate,
        ];
    }
}
