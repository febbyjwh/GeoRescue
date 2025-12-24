<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        District::truncate();
        $json = File::get('database/data/data_district.json');
        $states = json_decode($json);
        foreach ($states as $state) {
            District::create([
                'id'            =>  $state->id,
                'name'          =>  $state->name
            ]);
        }
    }
}
