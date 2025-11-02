<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VilleSeeder::class,
            // Create students (each student will create their own user account)
            EtudiantSeeder::class,
            // Create additional non-student users (admins, professors, etc.)
            UserSeeder::class,
        ]);
    }
}
