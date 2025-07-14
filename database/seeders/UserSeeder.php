<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Apotek;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get apotek data
        $apotekSejahtera = Apotek::where('nama_apotek', 'Apotek Sejahtera')->first();
        $apotekMedika = Apotek::where('nama_apotek', 'Apotek Medika')->first();
        $apotekKesehatan = Apotek::where('nama_apotek', 'Apotek Kesehatan')->first();
        $apotekCemerlang = Apotek::where('nama_apotek', 'Apotek Cemerlang')->first();
        $apotekHarmoni = Apotek::where('nama_apotek', 'Apotek Harmoni')->first();

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

        // Apoteker Users - Apotek Sejahtera
        User::updateOrCreate(
            ['email' => 'apoteker@apoteksejahtera.com'],
            [
                'name' => 'Apt. Michael Chen',
                'email' => 'apoteker@apoteksejahtera.com',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'apotek_id' => $apotekSejahtera->id,
                'no_hp' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // Farmasi Users - Apotek Sejahtera
        User::updateOrCreate(
            ['email' => 'farmasi@apoteksejahtera.com'],
            [
                'name' => 'Farmasi Linda Sari',
                'email' => 'farmasi@apoteksejahtera.com',
                'password' => Hash::make('password'),
                'role' => 'farmasi',
                'apotek_id' => $apotekSejahtera->id,
                'no_hp' => '081234567891',
                'email_verified_at' => now(),
            ]
        );

        // Apoteker Users - Apotek Medika
        User::updateOrCreate(
            ['email' => 'apoteker@apotekmedika.com'],
            [
                'name' => 'Apt. Siti Nurhaliza',
                'email' => 'apoteker@apotekmedika.com',
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'apotek_id' => $apotekMedika->id,
                'no_hp' => '081234567892',
                'email_verified_at' => now(),
            ]
        );

        // Farmasi Users - Apotek Medika
        User::updateOrCreate(
            ['email' => 'farmasi@apotekmedika.com'],
            [
                'name' => 'Farmasi Budi Santoso',
                'email' => 'farmasi@apotekmedika.com',
                'password' => Hash::make('password'),
                'role' => 'farmasi',
                'apotek_id' => $apotekMedika->id,
                'no_hp' => '081234567893',
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
                'no_hp' => '081234567894',
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
                'no_hp' => '081234567895',
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
                'no_hp' => '081234567896',
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
                'no_hp' => '081234567897',
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
                'no_hp' => '081234567898',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@eprescription.com / password');
        $this->command->info('Dokter: dokter@eprescription.com / password');
        $this->command->info('Apoteker Apotek Sejahtera: apoteker@apoteksejahtera.com / password');
        $this->command->info('Farmasi Apotek Sejahtera: farmasi@apoteksejahtera.com / password');
        $this->command->info('Apoteker Apotek Medika: apoteker@apotekmedika.com / password');
        $this->command->info('Farmasi Apotek Medika: farmasi@apotekmedika.com / password');
        $this->command->info('Pasien: pasien1@eprescription.com - pasien5@eprescription.com / password');
    }
} 