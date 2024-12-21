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
            'is_active' => true,
        ]);

        User::create([
            'name' => 'userauditor',
            'profile_name' => 'Admin Auditor',
            'email' => 'auditor@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditor',
            'no_identity' => '1234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'userauditee',
            'profile_name' => 'Admin Auditee',
            'email' => 'auditee@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditee',
            'is_active' => true,
        ]);

        Navigation::insert([
            ['menu' => 'Dashboard', 'type' => 'standalone', 'url' => '/dashboard',  'icon' => "fa-solid fa-house", 'roles' => json_encode(['ppm', 'auditor', 'auditee'])],
            ['menu' => 'Kelola Peran', 'type' => 'standalone', 'url' => '/kelola-peran', 'icon' => "fa-solid fa-list", 'roles' => json_encode(['ppm'])],

            ['menu' => 'Master Data', 'type' => 'parent', 'url' => '', 'icon' => "fa-solid fa-database", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Standar Audit', 'type' => 'Master Data', 'url' => '/standar-audit', 'icon' => "fa-solid fa-bars-progress", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Pernyataan Standar', 'type' => 'hidden', 'url' => '/pernyataan-standar', 'icon' => "", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Unit Kerja', 'type' => 'Master Data', 'url' => '/unit-kerja', 'icon' => "fa-solid fa-people-group", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Auditor', 'type' => 'Master Data', 'url' => '/auditor', 'icon' => "fa-solid fa-stamp", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Periode Audit', 'type' => 'Master Data', 'url' => '/periode-audit', 'icon' => "fa-regular fa-clock", 'roles' => json_encode(['ppm'])],

            ['menu' => 'Jadwal Audit', 'type' => 'standalone', 'url' => '/jadwal-audit', 'icon' => "fa-solid fa-calendar-days", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Detail Jadwal Audit', 'type' => 'hidden', 'url' => '/detail-jadwal-audit', 'icon' => "", 'roles' => json_encode(['ppm'])],

            ['menu' => 'Penugasan Audit', 'type' => 'standalone', 'url' => '/penugasan-audit', 'icon' => "fa-solid fa-code-branch", 'roles' => json_encode(['ppm'])],
            ['menu' => 'Detail Penugasan Audit', 'type' => 'hidden', 'url' => '/detail-penugasan-audit', 'icon' => "", 'roles' => json_encode(['ppm'])],

            // ['menu' => 'Lembar Evaluasi Diri', 'type' => 'standalone', 'url' => '/lembar-evaluasi-diri', 'icon' => "fa-solid fa-code-branch", 'roles' => json_encode(['ppm', 'auditor', 'auditee'])],
            // ['menu' => 'Detail Lembar Evaluasi Diri', 'type' => 'hidden', 'url' => '/detail-lembar-evaluasi-diri', 'icon' => "", 'roles' => json_encode(['ppm', 'auditor', 'auditee'])],
        ]);
    }
}
