<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Navigation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::create([
            'name' => 'user1ppm',
            'profile_name' => 'Admin 1 PPM',
            'email' => 'ppm@example.com',
            'password' => bcrypt('password'),
            'role' => 'ppm',
        ]);

        User::create([
            'name' => 'user1auditor',
            'profile_name' => 'Admin 1 Auditor',
            'email' => 'auditor@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditor',
        ]);

        User::create([
            'name' => 'user1auditee',
            'profile_name' => 'Admin 1 Auditee',
            'email' => 'auditee@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditee',
        ]);

        Navigation::insert([
            ['menu' => 'Dashboard', 'url' => '/dashboard',  'icon' => "fa-solid fa-database", 'roles' => json_encode(['ppm', 'auditor', 'auditee'])],
            ['menu' => 'Manajemen Pengguna', 'url' => '/manajemen-pengguna', 'icon' => "fa-solid fa-user", 'roles' => json_encode(['ppm'])],
        ]);
    }
}
