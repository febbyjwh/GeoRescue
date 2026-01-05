<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\FasilitasVital;

class FasilitasVitalSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel
        FasilitasVital::truncate();

        // Ambil file JSON
        $json = File::get(database_path('data/fasilitas_vital.json'));
        $states = json_decode($json);

        foreach ($states as $state) {
            FasilitasVital::create([
                'nama_fasilitas'   => $state->nama_fasilitas,
                'jenis_fasilitas'  => $state->jenis_fasilitas,
                'alamat'           => $state->alamat,
                'desa'             => $state->desa,
                'kecamatan'        => $state->kecamatan,
                'latitude'         => $state->latitude,
                'longitude'        => $state->longitude,
                'status'           => $state->status,
            ]);
        }
    }
}
