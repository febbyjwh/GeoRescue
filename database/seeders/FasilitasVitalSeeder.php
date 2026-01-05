<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasVital;
use Illuminate\Support\Facades\File;

class FasilitasVitalSeeder extends Seeder
{
    public function run(): void
    {
        FasilitasVital::truncate();

        $json = File::get(database_path('data/fasilitas_vital.json'));
        $payload = json_decode($json, true); // jadi array biar gampang

        // cari item yang type = table dan name = fasilitas_vital
        $table = collect($payload)->first(function ($item) {
            return ($item['type'] ?? null) === 'table'
                && ($item['name'] ?? null) === 'fasilitas_vital';
        });

        $rows = $table['data'] ?? [];

        foreach ($rows as $row) {
            FasilitasVital::create([
                'nama_fasilitas'  => $row['nama_fasilitas'] ?? null,
                'jenis_fasilitas' => $row['jenis_fasilitas'] ?? null,
                'alamat'          => $row['alamat'] ?? null,
                'desa'            => $row['desa'] ?? null,
                'kecamatan'       => $row['kecamatan'] ?? null,
                'latitude'        => $row['latitude'] ?? null,
                'longitude'       => $row['longitude'] ?? null,
                'status'          => $row['status'] ?? null,
            ]);
        }
    }
}
