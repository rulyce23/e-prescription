<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apotek;

class ApotekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apotekData = [
            [
                'nama_apotek' => 'Apotek Sejahtera',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'telepon' => '021-5550123',
                'whatsapp' => '081234567890',
                'email' => 'info@apoteksejahtera.com',
                'is_active' => true,
            ],
            [
                'nama_apotek' => 'Apotek Medika',
                'alamat' => 'Jl. Thamrin No. 456, Jakarta Selatan',
                'telepon' => '021-5550456',
                'whatsapp' => '081234567891',
                'email' => 'contact@apotekmedika.com',
                'is_active' => true,
            ],
            [
                'nama_apotek' => 'Apotek Kesehatan',
                'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta Barat',
                'telepon' => '021-5550789',
                'whatsapp' => '081234567892',
                'email' => 'admin@apotekkesehatan.com',
                'is_active' => true,
            ],
            [
                'nama_apotek' => 'Apotek Cemerlang',
                'alamat' => 'Jl. Hayam Wuruk No. 321, Jakarta Utara',
                'telepon' => '021-5550321',
                'whatsapp' => '081234567893',
                'email' => 'info@apotekcemerlang.com',
                'is_active' => true,
            ],
            [
                'nama_apotek' => 'Apotek Harmoni',
                'alamat' => 'Jl. Asia Afrika No. 654, Bandung',
                'telepon' => '022-5550654',
                'whatsapp' => '081234567894',
                'email' => 'contact@apotekharmoni.com',
                'is_active' => true,
            ],
        ];

        foreach ($apotekData as $apotek) {
            Apotek::updateOrCreate(
                ['nama_apotek' => $apotek['nama_apotek']],
                $apotek
            );
        }
    }
} 