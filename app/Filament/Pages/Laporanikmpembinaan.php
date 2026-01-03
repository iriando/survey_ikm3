<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Kegiatan;
use Filament\Pages\Page;
use App\Models\RespondenPembinaan;
use App\Models\Pertanyaanikmpembinaan;
use Filament\Tables\Table;
use App\Models\RespondenIkmPembinaan;
use Filament\Actions\Action;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Laporanikmpembinaan extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporanikmpembinaan';
    protected static ?string $navigationGroup = 'IKM Pembinaan';
    protected static ?string $title = 'Laporan IKM Pembinaan';

    public ?string $kegiatan = null;

    public function getFormSchema(): array
    {
        return [
            Select::make('kegiatan')
                ->label('Kegiatan')
                ->options(
                    RespondenPembinaan::whereNotNull('kegiatan')
                        ->distinct()
                        ->pluck('kegiatan', 'kegiatan')
                        ->toArray()
                )
                ->searchable()
                ->reactive()
        ];
    }

    public function getDataResponden()
    {
        $pertanyaan = Pertanyaanikmpembinaan::with('unsur')->get();

        $selects = [
            'respondenpembinaans.id AS responden_id',
            'respondenpembinaans.nama AS nama_responden',
            'DATE(responden_ikm_pembinaans.updated_at) AS tanggal',
        ];

        foreach ($pertanyaan as $p) {
            $kd = $p->unsur->kd_unsur;
            $selects[] = "MAX(CASE WHEN kd_unsurikmpembinaan = '{$kd}' THEN skor END) AS `{$kd}`";
        }

        return DB::table('responden_ikm_pembinaans')
            ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
            ->whereNotNull('respondenpembinaans.kegiatan')
            ->selectRaw(implode(', ', $selects))
            ->groupBy(
                'respondenpembinaans.id',
                'respondenpembinaans.nama',
                DB::raw('DATE(responden_ikm_pembinaans.updated_at)')
            )
            ->orderBy('tanggal')
            ->get();
    }
    // public function getDataResponden()
    // {
    //     $pertanyaan = Pertanyaanikmpembinaan::all();

    //     $query = DB::table('responden_ikm_pembinaans')
    //         ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
    //         ->whereNotNull('respondenpembinaans.kegiatan')
    //         ->selectRaw('
    //             respondenpembinaans.id AS responden_id,
    //             respondenpembinaans.nama AS nama_responden,
    //             DATE(responden_ikm_pembinaans.updated_at) AS tanggal,
    //             ' . collect($pertanyaan)->map(function ($p) {
    //                 return "MAX(CASE WHEN kd_unsurikmpembinaan = '{$p->unsur->kd_unsur}' THEN skor END) AS `{$p->unsur->kd_unsur}`";
    //             })->implode(', ')
    //         );

    //     if ($this->kegiatan) {
    //         $query->where('respondenpembinaans.kegiatan', $this->kegiatan);
    //     }

    //     return $query
    //         ->groupBy('respondenpembinaans.id', 'respondenpembinaans.nama', DB::raw('DATE(responden_ikm_pembinaans.updated_at)'))
    //         ->orderBy('tanggal')
    //         ->get();
    // }

    public function getPertanyaan()
    {
        return Pertanyaanikmpembinaan::all();
    }

    public function getGenderCount()
    {
        $query = RespondenPembinaan::query()
            ->whereNotNull('kegiatan')
            ->whereHas('jawabansurvey');

        if ($this->kegiatan) {
            $query->where('kegiatan', $this->kegiatan);
        }

        return $query->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
    }

    public function getPendidikanCount()
    {
        $query = RespondenPembinaan::query()
            ->whereNotNull('kegiatan')
            ->whereHas('jawabansurvey');

        if ($this->kegiatan) {
            $query->where('kegiatan', $this->kegiatan);
        }

        return $query->select('pendidikan', DB::raw('count(*) as total'))
            ->groupBy('pendidikan')
            ->orderBy('pendidikan', 'asc')
            ->get();
    }

    public function getTotalPerParameter()
    {
        $query = DB::table('responden_ikm_pembinaans')
            ->whereNotNull('kegiatan')
            ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
            ->select('kd_unsurikmpembinaan', DB::raw('SUM(skor) as total_skor'));

        if ($this->kegiatan) {
            $query->where('respondenpembinaans.kegiatan', $this->kegiatan);
        }

        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    public function getAveragePerParameter()
    {
        $query = DB::table('responden_ikm_pembinaans')
            ->whereNotNull('kegiatan')
            ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
            ->select('kd_unsurikmpembinaan', DB::raw('FORMAT(AVG(skor), 2) as avg_skor'));

        if ($this->kegiatan) {
            $query->where('respondenpembinaans.kegiatan', $this->kegiatan);
        }
        // dd($query->toSql(), $query->getBindings());
        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    public function getSkmPerParameter()
    {
        $totalParameter = Pertanyaanikmpembinaan::count();
        $bobot = 1 / $totalParameter;
        $query = DB::table('responden_ikm_pembinaans')
            ->whereNotNull('kegiatan')
            ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
            ->select('kd_unsurikmpembinaan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"));

        if ($this->kegiatan) {
            $query->where('respondenpembinaans.kegiatan', $this->kegiatan);
        }
        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    public function getIkm()
    {
        $totalParameter = Pertanyaanikmpembinaan::count();
        $totalNp = NilaiPersepsiIkm::count();

        if ($totalParameter === 0 || $totalNp === 0) {
            return null;
        }

        $konversi = 100 / $totalNp;

        $query = DB::table('responden_ikm_pembinaans')
            ->join('respondenpembinaans', 'responden_ikm_pembinaans.id_biodata', '=', 'respondenpembinaans.id')
            ->whereNotNull('respondenpembinaans.kegiatan');

        if ($this->kegiatan) {
            $query->where('respondenpembinaans.kegiatan', $this->kegiatan);
        }

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('responden_ikm_pembinaans.id_biodata')->count('responden_ikm_pembinaans.id_biodata');

        if ($totalResponden === 0) {
            return 0;
        }

        $nrr = $totalSkor / ($totalResponden * $totalParameter);
        $ikm = $nrr * $konversi;

        return round($ikm, 2);
    }


    protected function getHeaderActions(): array
    {
        return [
            // Tombol untuk export per kegiatan
            Action::make('unduh_per_kegiatan')
            ->label('Per Kegiatan')
            ->color('success')
            ->icon('heroicon-m-arrow-down-tray')
            ->form([
                Select::make('kegiatan_id')
                    ->label('Pilih Kegiatan')
                    ->options(Kegiatan::orderBy('n_kegiatan')->pluck('n_kegiatan', 'id'))
                    ->required()
                    ->searchable(),
            ])
            ->action(function (array $data) {
                return redirect()->route('export.ikm-pembinaan', [
                    'id' => $data['kegiatan_id'],
                ]);
            })
            ->modalHeading('Pilih Kegiatan')
            ->modalButton('Unduh'),

            // Tombol untuk export per periode
            Action::make('unduh_per_periode')
                ->label('Periode')
                ->color('primary')
                ->icon('heroicon-m-calendar-days')
                ->form([
                    DatePicker::make('tanggalMulai')
                        ->label('Tanggal Mulai')
                        ->required(),
                    DatePicker::make('tanggalAkhir')
                        ->label('Tanggal Akhir')
                        ->required()
                        ->after('tanggalMulai'),
                ])
                ->action(function (array $data) {
                    return redirect()->route('export.ikm-pembinaan-periode', [
                        'tanggalMulai' => $data['tanggalMulai'],
                        'tanggalAkhir' => $data['tanggalAkhir'],
                    ]);
                })
                ->modalHeading('Pilih Periode')
                ->modalButton('Unduh'),
        ];
    }
}
