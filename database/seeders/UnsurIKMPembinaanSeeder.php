<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unsurikmpembinaan;
use App\Models\Pertanyaanikmpembinaan;
use App\Models\Pilihan_jawabanikmpembinaan;

class UnsurIKMPembinaanSeeder extends Seeder
{
    public function run(): void
    {
        $unsurs = [
            ['kd_unsur' => 'P1', 'nama_unsur' => 'Persyaratan', 'keterangan' => 'Jelas dan mudah dipahami'],
            ['kd_unsur' => 'P2', 'nama_unsur' => 'Sistem, Mekanisme dan Prosedur', 'keterangan' => 'Sederhana dan mudah dilaksanakan'],
            ['kd_unsur' => 'P3', 'nama_unsur' => 'Waktu Penyelesaian', 'keterangan' => 'Sesuai waktu yang dijanjikan'],
            ['kd_unsur' => 'P4', 'nama_unsur' => 'Produk Spesifikasi Jenis Pembinaan', 'keterangan' => 'Sesuai spesifikasi'],
            ['kd_unsur' => 'P5', 'nama_unsur' => 'Kompetensi Pelaksana', 'keterangan' => 'Memiliki kemampuan sesuai bidang tugas'],
            ['kd_unsur' => 'P6', 'nama_unsur' => 'Perilaku Pelaksana', 'keterangan' => 'Ramah dan sopan'],
            ['kd_unsur' => 'P7', 'nama_unsur' => 'Sarana dan Prasarana', 'keterangan' => 'Memadai dan nyaman'],
            ['kd_unsur' => 'P8', 'nama_unsur' => 'Penanganan Pengaduan', 'keterangan' => 'Cepat dan jelas'],
        ];

        foreach ($unsurs as $unsurData) {
            $unsur = Unsurikmpembinaan::create($unsurData);

            // Buat pertanyaan untuk unsur
            $pertanyaan = Pertanyaanikmpembinaan::create([
                'unsur_id' => $unsur->id,
                'teks_pertanyaan' => "Bagaimana pendapat Saudara tentang {$unsur->nama_unsur}?",
            ]);

            // Buat pilihan jawaban untuk pertanyaan ini
            $pilihans = [
                ['teks_pilihan' => 'Tidak Baik', 'np' => 1, 'bobot' => 2.6, 'mutu' => 'D'],
                ['teks_pilihan' => 'Kurang Baik', 'np' => 2, 'bobot' => 3.06, 'mutu' => 'C'],
                ['teks_pilihan' => 'Baik', 'np' => 3, 'bobot' => 3.53, 'mutu' => 'B'],
                ['teks_pilihan' => 'Sangat Baik', 'np' => 4, 'bobot' => 4, 'mutu' => 'A'],
            ];

            foreach ($pilihans as $pilihan) {
                $pertanyaan->pilihanJawabans()->create($pilihan);
            }
        }
    }
}
