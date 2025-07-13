<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Pasien user
        User::create([
            'name' => 'Pasien Test',
            'email' => 'pasien@test.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
        ]);

        // Create Apoteker user
        User::create([
            'name' => 'Apoteker Test',
            'email' => 'apoteker@test.com',
            'password' => Hash::make('password'),
            'role' => 'apoteker',
        ]);

        // Create Admin user (if not exists)
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
