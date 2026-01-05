<?php

namespace Database\Seeders;

use App\Models\PoskoBencana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PoskoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PoskoBencana::truncate(); // Kosongin dulu tabel

        $json = File::get(database_path('data/posko.json'));
        $poskos = json_decode($json);

        foreach ($poskos as $posko) {
            PoskoBencana::create([
                "nama_posko" => $posko->nama_posko,
                "jenis_posko" => $posko->jenis_posko,
                "alamat_posko" => $posko->alamat_posko ?? null,
                "kecamatan_id" => $posko->kecamatan_id,
                "desa_id" => $posko->desa_id,
                "latitude" => $posko->latitude,
                "longitude" => $posko->longitude,
                "status_posko" => $posko->status_posko
            ]);
        }
    }
}