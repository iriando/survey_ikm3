<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IkmPerPokjaChart extends ChartWidget
{
    protected static ?string $heading = 'IKM Per Pokja';
    protected static ?int $sort = 4;
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

        $pokjas = DB::table('layanans')
            ->select('pokja')
            ->distinct()
            ->pluck('pokja');

        $ikmPerPokja = [];

        foreach ($pokjas as $pokja) {
            // ambil semua layanan dari pokja ini
            $layanans = DB::table('layanans')
                ->where('pokja', $pokja)
                ->pluck('j_layanan');

            $query = DB::table('responden_ikm_pelayanans')
                ->join('respondenpelayanans', 'responden_ikm_pelayanans.id_biodata', '=', 'respondenpelayanans.id')
                ->whereIn('respondenpelayanans.j_layanan', $layanans)
                ->whereYear('responden_ikm_pelayanans.created_at', $tahun);

            $totalSkor = $query->sum('skor');
            $totalResponden = $query->distinct('responden_ikm_pelayanans.id_biodata')
                ->count('responden_ikm_pelayanans.id_biodata');

            $ikm = $totalResponden > 0
                ? round(($totalSkor / $totalResponden) * $bobot * $konversi, 2)
                : 0;

            if ($ikm > 0) {
                $ikmPerPokja[$pokja] = $ikm;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'IKM per Pokja',
                    'data' => array_values($ikmPerPokja),
                    'backgroundColor' => '#FF9800',
                    'borderColor' => '#F57C00',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => array_keys($ikmPerPokja),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
