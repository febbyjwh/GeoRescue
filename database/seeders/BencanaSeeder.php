<?php

namespace Database\Seeders;

use App\Models\Bencana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BencanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bencana::truncate();
        $json = File::get('database/data/bencana.json');
        $states = json_decode($json);
        foreach ($states as $state) {
            Bencana::create([
                "kecamatan_id" => $state->kecamatan_id,
                "desa_id" => $state->desa_id,
                "jenis_bencana" => $state->jenis_bencana,
                "tingkat_kerawanan" => $state->tingkat_kerawanan,
                "nilai" => $state->nilai,
                "satuan" => $state->satuan,
                "status" => $state->status,
                "lang" => $state->lang,
                "lat" => $state->lat
            ]);
        }
    }
}
