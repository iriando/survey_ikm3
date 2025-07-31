<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NilaiPersepsiIkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'np' => '4',
                'ni_terendah' => 3.5324,
                'ni_tertinggi' => 4.00,
                'nik_terendah' => 88.31,
                'nik_tertinggi' => 100.00,
                'mutu_pelayanan' => 'A',
                'kinerja' => 'Sangat Baik',
            ],
            [
                'np' => '3',
                'ni_terendah' => 3.0644,
                'ni_tertinggi' => 3.532,
                'nik_terendah' => 76.61,
                'nik_tertinggi' => 88.30,
                'mutu_pelayanan' => 'B',
                'kinerja' => 'Baik',
            ],
            [
                'np' => '2',
                'ni_terendah' => 2.60,
                'ni_tertinggi' => 3.064,
                'nik_terendah' => 65.00,
                'nik_tertinggi' => 76.60,
                'mutu_pelayanan' => 'C',
                'kinerja' => 'Kurang Baik',
            ],
            [
                'np' => '1',
                'ni_terendah' => 1.00,
                'ni_tertinggi' => 2.5996,
                'nik_terendah' => 25.00,
                'nik_tertinggi' => 64.99,
                'mutu_pelayanan' => 'D',
                'kinerja' => 'Tidak Baik',
            ],
        ];

        foreach ($data as $item) {
            NilaiPersepsiIkm::create($item);
        }
    }
}
