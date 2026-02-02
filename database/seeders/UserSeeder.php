<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'name' => 'Super Admin',
        'username' => 'superadmin',
        'email' => null,
        'password' => Hash::make('admin123'),
        'role' => 'super_admin',
        'change_password' => false
    ]);

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => null,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'change_password' => true
        ]);
    }
}
