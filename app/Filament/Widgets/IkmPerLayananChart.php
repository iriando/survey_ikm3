<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IkmPerLayananChart extends ChartWidget
{
    protected static ?string $heading = 'IKM Per Layanan';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tahun = date('Y');
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

        // ambil semua layanan
        $layanans = DB::table('layanans')->pluck('j_layanan', 'id');

        $ikmPerLayanan = [];

        foreach ($layanans as $id => $namaLayanan) {
            $query = DB::table('responden_ikms')
                ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
                ->where('respondens.j_layanan', $namaLayanan)
                ->whereYear('responden_ikms.created_at', $tahun);

            $totalSkor = $query->sum('skor');
            $totalResponden = $query->distinct('responden_ikms.id_biodata')->count('responden_ikms.id_biodata');

            $ikm = $totalResponden > 0
                ? round(($totalSkor / $totalResponden) * $bobot * $konversi, 2)
                : 0;

            if ($ikm > 0) {
                $ikmPerLayanan[$namaLayanan] = $ikm;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'IKM per Layanan',
                    'data' => array_values($ikmPerLayanan),
                    'backgroundColor' => '#2196F3',
                    'borderColor' => '#1976D2',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => array_keys($ikmPerLayanan)
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
