<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resep;
use App\Models\User;
use App\Models\ObatalkesM;

class DashboardSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $pasien = User::where('role', 'pasien')->first();
        $dokter = User::where('role', 'dokter')->first();
        $apoteker = User::where('role', 'apoteker')->first();

        if (!$pasien || !$dokter || !$apoteker) {
            $this->command->error('Required users not found. Please run UserSeeder first.');
            return;
        }

        // Create sample prescriptions with different statuses
        $statuses = ['draft', 'pending', 'approved', 'rejected', 'processing', 'completed'];
        
        foreach ($statuses as $status) {
            for ($i = 0; $i < 3; $i++) {
                $resep = new Resep();
                $resep->no_resep = 'RSP' . date('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $resep->nama_pasien = 'Pasien Sample ' . ($i + 1);
                $resep->tanggal_resep = now();
                $resep->status = $status;
                $resep->user_id = $pasien->id;
                $resep->created_at = now()->subDays(rand(1, 30));
                $resep->save();
                
                // Set approval/rejection data
                if ($status === 'approved') {
                    $resep->approved_by = $dokter->id;
                    $resep->approved_at = now()->subDays(rand(1, 7));
                    $resep->save();
                } elseif ($status === 'rejected') {
                    $resep->rejected_by = $dokter->id;
                    $resep->rejected_at = now()->subDays(rand(1, 7));
                    $resep->save();
                } elseif ($status === 'processing') {
                    $resep->approved_by = $dokter->id;
                    $resep->approved_at = now()->subDays(rand(3, 10));
                    $resep->received_by = $apoteker->id;
                    $resep->received_at = now()->subDays(rand(1, 3));
                    $resep->save();
                } elseif ($status === 'completed') {
                    $resep->approved_by = $dokter->id;
                    $resep->approved_at = now()->subDays(rand(5, 15));
                    $resep->received_by = $apoteker->id;
                    $resep->received_at = now()->subDays(rand(3, 8));
                    $resep->completed_by = $apoteker->id;
                    $resep->completed_at = now()->subDays(rand(1, 5));
                    $resep->save();
                }
            }
        }

        // Create some low stock items
        $obatalkes = ObatalkesM::inRandomOrder()->limit(5)->get();
        foreach ($obatalkes as $obat) {
            $obat->stok = rand(1, 10);
            $obat->save();
        }

        // Create some out of stock items
        $obatalkes = ObatalkesM::inRandomOrder()->limit(3)->get();
        foreach ($obatalkes as $obat) {
            $obat->stok = 0;
            $obat->save();
        }

        $this->command->info('Dashboard sample data created successfully!');
    }
} 