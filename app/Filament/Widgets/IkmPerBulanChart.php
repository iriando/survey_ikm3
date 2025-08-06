<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IkmPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'IKM Per Bulan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $totalParameter = DB::table('pertanyaanikmpelayanans')->count();
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

        $bulanIndo = [
            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
        ];

        for ($i = 1; $i <= 12; $i++) {
            $query = DB::table('responden_ikms')
                ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
                ->whereNotNull('respondens.j_layanan')
                ->whereMonth('responden_ikms.created_at', $i);

            $totalSkor = $query->sum('skor');
            $totalResponden = $query->distinct('responden_ikms.id_biodata')->count('responden_ikms.id_biodata');

            $ikm = $totalResponden > 0
                ? round(($totalSkor / $totalResponden) * $bobot * $konversi, 2)
                : 0;

            if ($ikm > 0) {
                $ikmPerBulan[$i] = $ikm;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'IKM Bulanan',
                    'data' => array_values($ikmPerBulan),
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#388E3C',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => array_map(fn($key) => $bulanIndo[$key], array_keys($ikmPerBulan))
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
