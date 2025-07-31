<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IkmPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'IKM Per Bulan';
    protected static ?int $sort = 2; // Urutan widget pada dashboard
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $totalParameter = DB::table('pertanyaan_ikms')->count();
        $totalNp = DB::table('nilai_persepsi_ikms')->count();

        if ($totalParameter == 0 || $totalNp == 0) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;
        $ikmPerBulan = [];

        // Nama bulan dalam bahasa Indonesia
        $bulanIndo = [
            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
        ];

        for ($i = 1; $i <= 12; $i++) {
            $query = DB::table('responden_ikms')
                ->select(DB::raw("FORMAT((
                    SUM(skor) / COUNT(skor) * $bobot
                ) * $konversi, 2) AS ikm"))
                ->whereMonth('created_at', $i)
                ->first();

            if (!empty($query->ikm) && $query->ikm != '0.00') {
                $ikmPerBulan[$i] = (float) $query->ikm;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'IKM Bulanan',
                    'data' => array_values($ikmPerBulan), // Nilai IKM
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#388E3C',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => array_map(fn($key) => $bulanIndo[$key], array_keys($ikmPerBulan)) // Nama bulan
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Menggunakan chart bar
    }
}
