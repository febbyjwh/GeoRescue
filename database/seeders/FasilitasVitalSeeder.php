<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasVital;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\File;

class FasilitasVitalSeeder extends Seeder
{
    public function run(): void
    {
        FasilitasVital::truncate();

        $json = File::get(database_path('data/fasilitas_vital.json'));
        $payload = json_decode($json, true);

        $table = collect($payload)->first(fn ($item) =>
            ($item['type'] ?? null) === 'table'
            && ($item['name'] ?? null) === 'fasilitas_vital'
        );

        $rows = $table['data'] ?? [];

        foreach ($rows as $row) {

            $kecamatan = Kecamatan::where('name', $row['kecamatan'])->first();
            $desa      = Desa::where('name', $row['desa'])->first();

            // skip kalau relasi tidak ketemu
            if (!$kecamatan || !$desa) {
                continue;
            }

            FasilitasVital::create([
                'nama_fasilitas'  => $row['nama_fasilitas'],
                'jenis_fasilitas' => $row['jenis_fasilitas'],
                'alamat'          => $row['alamat'],
                'kecamatan_id'    => $kecamatan->id,
                'desa_id'         => $desa->id,
                'latitude'        => $row['latitude'],
                'longitude'       => $row['longitude'],
                'status'          => $row['status'],
            ]);
        }
    }
}
