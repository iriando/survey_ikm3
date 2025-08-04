<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unsurikmpelayanan;
use App\Models\Pertanyaanikmpelayanan;
use App\Models\Pilihan_jawabanikmpelayanan;

class UnsurIKMPelayananSeeder extends Seeder
{
    public function run(): void
    {
        $unsurs = [
            ['kd_unsur' => 'U1', 'nama_unsur' => 'Persyaratan', 'keterangan' => 'Jelas dan mudah dipahami'],
            ['kd_unsur' => 'U2', 'nama_unsur' => 'Sistem, Mekanisme dan Prosedur', 'keterangan' => 'Sederhana dan mudah dilaksanakan'],
            ['kd_unsur' => 'U3', 'nama_unsur' => 'Waktu Penyelesaian', 'keterangan' => 'Sesuai waktu yang dijanjikan'],
            ['kd_unsur' => 'U4', 'nama_unsur' => 'Biaya', 'keterangan' => 'Sesuai ketentuan dan transparan'],
            ['kd_unsur' => 'U5', 'nama_unsur' => 'Produk Spesifikasi Jenis Pembinaan', 'keterangan' => 'Sesuai spesifikasi'],
            ['kd_unsur' => 'U6', 'nama_unsur' => 'Kompetensi Pelaksana', 'keterangan' => 'Memiliki kemampuan sesuai bidang tugas'],
            ['kd_unsur' => 'U7', 'nama_unsur' => 'Perilaku Pelaksana', 'keterangan' => 'Ramah dan sopan'],
            ['kd_unsur' => 'U8', 'nama_unsur' => 'Sarana dan Prasarana', 'keterangan' => 'Memadai dan nyaman'],
            ['kd_unsur' => 'U9', 'nama_unsur' => 'Penanganan Pengaduan', 'keterangan' => 'Cepat dan jelas'],
        ];

        foreach ($unsurs as $unsurData) {
            $unsur = Unsurikmpelayanan::create($unsurData);

            // Buat pertanyaan untuk unsur
            $pertanyaan = Pertanyaanikmpelayanan::create([
                'unsur_id' => $unsur->id,
                'teks_pertanyaan' => "Bagaimana pendapat Saudara tentang {$unsur->nama_unsur}?",
            ]);

            // Buat pilihan jawaban untuk pertanyaan ini
            $pilihans = [
                ['teks_pilihan' => 'Tidak Baik', 'np' => 1, 'mutu' => 'D'],
                ['teks_pilihan' => 'Kurang Baik', 'np' => 2, 'mutu' => 'C'],
                ['teks_pilihan' => 'Baik', 'np' => 3, 'mutu' => 'B'],
                ['teks_pilihan' => 'Sangat Baik', 'np' => 4, 'mutu' => 'A'],
            ];

            foreach ($pilihans as $pilihan) {
                $pertanyaan->pilihanJawabans()->create($pilihan);
            }
        }
    }
}
