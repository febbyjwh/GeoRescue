<?php

namespace Database\Seeders;

use App\Models\FasilitasVital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FasilitasVitalSeeder extends Seeder
{
    public function run(): void
    {
        FasilitasVital::truncate();

        $json = File::get(database_path('data/fasilitas_vital.json'));
        $states = json_decode($json);

        foreach ($states as $state) {
            FasilitasVital::create([
                "nama_fasilitas"  => $state->nama_fasilitas,
                "jenis_fasilitas" => $state->jenis_fasilitas,
                "alamat"          => $state->alamat,
                "kecamatan_id"    => $state->kecamatan_id,
                "desa_id"         => $state->desa_id,
                "fasilitas_lat"        => $state->latitude,
                "fasilitas_lng"       => $state->longitude,
                "fasilitas_status"          => $state->status
            ]);
        }
    }
}
