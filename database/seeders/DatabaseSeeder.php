<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin RMSP',
            'username' => 'admin',
            'email' => 'admin@rmsp.local',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Create Sample Karyawan
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@rmsp.local',
            'password' => bcrypt('password123'),
            'role' => 'karyawan',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'username' => 'siti',
            'email' => 'siti@rmsp.local',
            'password' => bcrypt('password123'),
            'role' => 'karyawan',
        ]);
    }
}
