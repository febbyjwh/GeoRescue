<?php

namespace Database\Seeders;

use App\Models\JalurDistribusiLogistik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LogistikSeeder extends Seeder
{
    public function run(): void
    {
        JalurDistribusiLogistik::truncate();

        $json = File::get(database_path('data/logistik.json'));
        $logistiks = json_decode($json);

        foreach ($logistiks as $item) {

            // NORMALISASI ENUM
            $jenis = strtolower($item->jenis_logistik);

            if (str_contains($jenis, 'all')) {
                $jenis = 'all(pangan, sandang, kesehatan, hunian)';
            }

            JalurDistribusiLogistik::create([
                'nama_lokasi'    => $item->nama_lokasi,
                'district_id'    => $item->district_id,
                'village_id'     => $item->village_id,
                'jenis_logistik' => $jenis,
                'jumlah'         => $item->jumlah,
                'satuan'         => $item->satuan,
                'status'         => $item->status,
                'lat'            => $item->lat,
                'lng'           => $item->lng,
            ]);
        }

        $this->command->info('Logistik data seeded successfully!');
    }
}