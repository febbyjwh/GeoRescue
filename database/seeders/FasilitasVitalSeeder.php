<?php

namespace Database\Seeders;

use App\Models\FasilitasVital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasVitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FasilitasVital::truncate();
        $json = File::get('database/data/fasilitas_vital.json');
        $states = json_decode($json);
        foreach ($states as $state) {
            FasilitasVital::create([
                "nama_fasilitas" => $state->nama_fasilitas,
                "jenis_fasilitas" => $state->jenis_fasilitas,
                "alamat" => $state->alamat,
                "desa" => $state->desa,
                "kecamatan" => $state->kecamatan,
                "latitude" => $state->latitude,
                "longitude" => $state->longitude,
                "status" => $state->status,
            ]);
        }
    }
}
