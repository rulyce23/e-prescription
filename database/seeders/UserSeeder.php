<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@eprescription.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Dokter User
        User::updateOrCreate(
            ['email' => 'dokter@eprescription.com'],
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'dokter@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'email_verified_at' => now(),
            ]
        );

        // Apoteker User
        User::updateOrCreate(
            ['email' => 'apoteker@eprescription.com'],
            [
                'name' => 'Apt. Michael Chen',
                'email' => 'apoteker@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'email_verified_at' => now(),
            ]
        );

        // Pasien Users
        User::updateOrCreate(
            ['email' => 'pasien1@eprescription.com'],
            [
                'name' => 'Budi Santoso',
                'email' => 'pasien1@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'pasien2@eprescription.com'],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'pasien2@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'pasien3@eprescription.com'],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'pasien3@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'pasien4@eprescription.com'],
            [
                'name' => 'Dewi Sartika',
                'email' => 'pasien4@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'pasien5@eprescription.com'],
            [
                'name' => 'Rudi Hartono',
                'email' => 'pasien5@eprescription.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@eprescription.com / password');
        $this->command->info('Dokter: dokter@eprescription.com / password');
        $this->command->info('Apoteker: apoteker@eprescription.com / password');
        $this->command->info('Pasien: pasien1@eprescription.com - pasien5@eprescription.com / password');
    }
} 