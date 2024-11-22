<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::create([
            'name' => 'PPM User',
            'email' => 'ppm@example.com',
            'password' => bcrypt('password'),
            'role' => 'ppm',
        ]);

        User::create([
            'name' => 'Auditor User',
            'email' => 'auditor@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditor',
        ]);

        User::create([
            'name' => 'Auditee User',
            'email' => 'auditee@example.com',
            'password' => bcrypt('password'),
            'role' => 'auditee',
        ]);
    }
}
