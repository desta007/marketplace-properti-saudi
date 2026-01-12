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
        // Run city and district seeders first
        $this->call([
            CitySeeder::class,
            DistrictSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@saudiprop.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'language' => 'en',
            'email_verified_at' => now(),
        ]);

        // Create test agent
        User::create([
            'name' => 'Ahmed Al-Rashid',
            'email' => 'agent@saudiprop.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
            'language' => 'ar',
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@saudiprop.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'language' => 'ar',
            'email_verified_at' => now(),
        ]);

        // Run property seeders
        $this->call([
            PropertySeeder::class,
            PropertyImageSeeder::class,
        ]);
    }
}
