<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SignaM;

class SignaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $signaData = [
            [
                'signa_kode' => 'S001',
                'signa_nama' => '3x1 tablet sehari',
                'additional_data' => 'Diminum 3 kali sehari, 1 tablet setiap kali minum'
            ],
            [
                'signa_kode' => 'S002',
                'signa_nama' => '2x1 tablet sehari',
                'additional_data' => 'Diminum 2 kali sehari, 1 tablet setiap kali minum'
            ],
            [
                'signa_kode' => 'S003',
                'signa_nama' => '1x1 tablet sehari',
                'additional_data' => 'Diminum 1 kali sehari, 1 tablet'
            ],
            [
                'signa_kode' => 'S004',
                'signa_nama' => '3x1 kapsul sehari',
                'additional_data' => 'Diminum 3 kali sehari, 1 kapsul setiap kali minum'
            ],
            [
                'signa_kode' => 'S005',
                'signa_nama' => '2x1 kapsul sehari',
                'additional_data' => 'Diminum 2 kali sehari, 1 kapsul setiap kali minum'
            ],
            [
                'signa_kode' => 'S006',
                'signa_nama' => '1x1 kapsul sehari',
                'additional_data' => 'Diminum 1 kali sehari, 1 kapsul'
            ],
            [
                'signa_kode' => 'S007',
                'signa_nama' => '3x1 sendok teh sehari',
                'additional_data' => 'Diminum 3 kali sehari, 1 sendok teh setiap kali minum'
            ],
            [
                'signa_kode' => 'S008',
                'signa_nama' => '2x1 sendok teh sehari',
                'additional_data' => 'Diminum 2 kali sehari, 1 sendok teh setiap kali minum'
            ],
            [
                'signa_kode' => 'S009',
                'signa_nama' => '1x1 sendok teh sehari',
                'additional_data' => 'Diminum 1 kali sehari, 1 sendok teh'
            ],
            [
                'signa_kode' => 'S010',
                'signa_nama' => 'Sesuai kebutuhan',
                'additional_data' => 'Diminum sesuai kebutuhan atau gejala'
            ]
        ];

        foreach ($signaData as $signa) {
            SignaM::create([
                'signa_kode' => $signa['signa_kode'],
                'signa_nama' => $signa['signa_nama'],
                'additional_data' => $signa['additional_data'],
                'created_date' => now(),
                'is_active' => true,
                'is_deleted' => false
            ]);
        }
    }
} 