<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ObatalkesM;

class ObatalkesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obatalkesData = [
            [
                'obatalkes_kode' => 'OBT001',
                'obatalkes_nama' => 'Paracetamol 500mg Tablet',
                'stok' => 100.00,
                'additional_data' => 'Obat penurun demam dan pereda nyeri'
            ],
            [
                'obatalkes_kode' => 'OBT002',
                'obatalkes_nama' => 'Ibuprofen 400mg Tablet',
                'stok' => 75.00,
                'additional_data' => 'Obat anti inflamasi non steroid'
            ],
            [
                'obatalkes_kode' => 'OBT003',
                'obatalkes_nama' => 'Amoxicillin 500mg Kapsul',
                'stok' => 50.00,
                'additional_data' => 'Antibiotik golongan penicillin'
            ],
            [
                'obatalkes_kode' => 'OBT004',
                'obatalkes_nama' => 'Omeprazole 20mg Kapsul',
                'stok' => 60.00,
                'additional_data' => 'Obat untuk asam lambung'
            ],
            [
                'obatalkes_kode' => 'OBT005',
                'obatalkes_nama' => 'Cetirizine 10mg Tablet',
                'stok' => 80.00,
                'additional_data' => 'Obat anti alergi'
            ],
            [
                'obatalkes_kode' => 'OBT006',
                'obatalkes_nama' => 'Loratadine 10mg Tablet',
                'stok' => 70.00,
                'additional_data' => 'Obat anti alergi generasi kedua'
            ],
            [
                'obatalkes_kode' => 'OBT007',
                'obatalkes_nama' => 'Metformin 500mg Tablet',
                'stok' => 45.00,
                'additional_data' => 'Obat untuk diabetes mellitus'
            ],
            [
                'obatalkes_kode' => 'OBT008',
                'obatalkes_nama' => 'Amlodipine 5mg Tablet',
                'stok' => 55.00,
                'additional_data' => 'Obat untuk hipertensi'
            ],
            [
                'obatalkes_kode' => 'OBT009',
                'obatalkes_nama' => 'Simvastatin 20mg Tablet',
                'stok' => 40.00,
                'additional_data' => 'Obat untuk menurunkan kolesterol'
            ],
            [
                'obatalkes_kode' => 'OBT010',
                'obatalkes_nama' => 'Lansoprazole 30mg Kapsul',
                'stok' => 65.00,
                'additional_data' => 'Obat untuk asam lambung'
            ],
            [
                'obatalkes_kode' => 'ALK001',
                'obatalkes_nama' => 'Masker Medis 3 Ply',
                'stok' => 200.00,
                'additional_data' => 'Masker untuk penggunaan medis'
            ],
            [
                'obatalkes_kode' => 'ALK002',
                'obatalkes_nama' => 'Hand Sanitizer 100ml',
                'stok' => 150.00,
                'additional_data' => 'Pembersih tangan antiseptik'
            ],
            [
                'obatalkes_kode' => 'ALK003',
                'obatalkes_nama' => 'Termometer Digital',
                'stok' => 25.00,
                'additional_data' => 'Alat pengukur suhu tubuh'
            ],
            [
                'obatalkes_kode' => 'ALK004',
                'obatalkes_nama' => 'Tensimeter Digital',
                'stok' => 15.00,
                'additional_data' => 'Alat pengukur tekanan darah'
            ],
            [
                'obatalkes_kode' => 'ALK005',
                'obatalkes_nama' => 'Stetoskop',
                'stok' => 20.00,
                'additional_data' => 'Alat untuk mendengarkan suara tubuh'
            ]
        ];

        foreach ($obatalkesData as $obatalkes) {
            ObatalkesM::create([
                'obatalkes_kode' => $obatalkes['obatalkes_kode'],
                'obatalkes_nama' => $obatalkes['obatalkes_nama'],
                'stok' => $obatalkes['stok'],
                'additional_data' => $obatalkes['additional_data'],
                'created_date' => now(),
                'is_active' => true,
                'is_deleted' => false
            ]);
        }
    }
} 