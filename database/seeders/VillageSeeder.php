<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Village::truncate();
        $json = File::get('database/data/data_village.json');
        $states = json_decode($json);
        foreach ($states as $state) {
            Village::create([
                'id'            =>  $state->id,
                'district_id'   =>  $state->district_id,
                'name'          =>  $state->name
            ]);
        }
    }
}
