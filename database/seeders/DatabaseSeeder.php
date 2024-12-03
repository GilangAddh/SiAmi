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
            'name' => 'userppm',
            'profile_name' => 'Admin PPM',
            'email' => 'ppm@example.com',
            'password' => bcrypt('password'),
            'role' => 'ppm',
        ]);

        User::create([
            'name' => 'userauditor',
            'profile_name' => 'Admin Auditor',
            'email' => 'auditor@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditor',
        ]);

        User::create([
            'name' => 'userauditee',
            'profile_name' => 'Admin Auditee',
            'email' => 'auditee@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditee',
        ]);

        Navigation::insert([
            ['menu' => 'Dashboard', 'url' => '/dashboard',  'icon' => "fa-solid fa-database", 'roles' => json_encode(['ppm', 'auditor', 'auditee'])],
            ['menu' => 'Manajemen Pengguna', 'url' => '/manajemen-pengguna', 'icon' => "fa-solid fa-user", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Manajemen Menu', 'url' => '/manajemen-menu', 'icon' => "fa-solid fa-list", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Standar Audit', 'url' => '/standar-audit', 'icon' => "fa-solid fa-gavel", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Indikator Standar Audit', 'url' => '/indikator-standar-audit', 'icon' => "fa-solid fa-question", 'roles' => json_encode(['ppm'])],
        ]);
    }
}
