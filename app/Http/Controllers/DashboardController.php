<?php

namespace App\Http\Controllers;

use App\Models\Bencana;
use App\Models\PoskoBencana;
use App\Models\FasilitasVital;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBencana = Bencana::count();
        $bencanaTinggi = Bencana::where('tingkat_kerawanan', 'Tinggi')->count();

        $jenisBencana = Bencana::select('jenis_bencana', DB::raw('count(*) as total'))
            ->groupBy('jenis_bencana')
            ->pluck('total', 'jenis_bencana');

        $start = $request->input('start_date', now()->startOfYear()->format('Y-m-d')); // default awal tahun
        $end = $request->input('end_date', now()->endOfYear()->format('Y-m-d'));

        $bencanaPerBulanQuery = Bencana::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        $bencanaPerBulan = [];
        foreach ($months as $num => $name) {
            $record = $bencanaPerBulanQuery->firstWhere('month', $num);
            $bencanaPerBulan[$name] = $record ? (int)$record->total : 0;
        }

        $totalPosko = PoskoBencana::count();
        $poskoAktif = PoskoBencana::where('status_posko', 'Aktif')->count();
        $poskoPenuh = PoskoBencana::where('status_posko', 'Penuh')->count();
        $poskoTutup = PoskoBencana::where('status_posko', 'Tutup')->count();

        $totalFasilitas = FasilitasVital::count();
        $fasilitasBeroperasi = FasilitasVital::where('status', 'Beroperasi')->count();
        $fasilitasTidakTersedia = FasilitasVital::where('status', 'Tidak Tersedia')->count();

        return view('dashboard', compact(
            'totalBencana',
            'bencanaTinggi',
            'jenisBencana',
            'bencanaPerBulan',
            'start',
            'end',
            'totalPosko',
            'poskoAktif',
            'poskoPenuh',
            'poskoTutup',
            'totalFasilitas',
            'fasilitasBeroperasi',
            'fasilitasTidakTersedia',
        ));
    }
}
