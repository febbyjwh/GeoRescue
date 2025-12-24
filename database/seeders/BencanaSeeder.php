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
                "kecamatan" => $state->kecamatan,
                "desa" => $state->desa,
                "nama_bencana" => $state->nama_bencana,
                "tingkat_kerawanan" => $state->tingkat_kerawanan,
                "lang" => $state->lang,
                "lat" => $state->lat
            ]);
        }
    }
}
