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
        // Call seeders in order
        $this->call([
            ApotekSeeder::class,
            UserSeeder::class,
            SignaSeeder::class,
            ObatalkesSeeder::class,
        ]);
    }
}
