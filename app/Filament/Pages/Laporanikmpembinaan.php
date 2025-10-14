<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Kegiatan;
use Filament\Pages\Page;
use App\Models\Responden;
use App\Models\Pertanyaan;
use Filament\Tables\Table;
use App\Models\RespondenIkm;
use Filament\Actions\Action;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;

class Laporanikmpembinaan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporanikmpembinaan';
    protected static ?string $navigationGroup = 'IKM Pembinaan';
    protected static ?string $title = 'Laporan';

    public ?string $kegiatan = null;

    public function getFormSchema(): array
    {
        return [
            Select::make('kegiatan')
                ->label('Kegiatan')
                ->options(
                    Responden::whereNotNull('kegiatan')
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
        $pertanyaan = Pertanyaan::all();

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('kegiatan')
            ->selectRaw('respondens.nama AS nama_responden, ' . collect($pertanyaan)->map(function ($p) {
                return "MAX(CASE WHEN kd_unsurikmpembinaan = '{$p->unsur->kd_unsur}' THEN skor END) AS `{$p->unsur->kd_unsur}`";
            })->implode(', ') . ', DATE(responden_ikms.updated_at) as tanggal');

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }

        return $query->groupBy('respondens.nama', 'tanggal')->get();
    }

    public function getPertanyaan()
    {
        return Pertanyaan::all();
    }

    public function getGenderCount()
    {
        $query = Responden::query()
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
        $query = Responden::query()
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
        $query = DB::table('responden_ikms')
            ->whereNotNull('kegiatan')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->select('kd_unsurikmpembinaan', DB::raw('SUM(skor) as total_skor'));

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }

        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    public function getAveragePerParameter()
    {
        $query = DB::table('responden_ikms')
            ->whereNotNull('kegiatan')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->select('kd_unsurikmpembinaan', DB::raw('FORMAT(AVG(skor), 2) as avg_skor'));

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }
        // dd($query->toSql(), $query->getBindings());
        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    public function getSkmPerParameter()
    {
        $totalParameter = Pertanyaan::count();
        $bobot = 1 / $totalParameter;
        $query = DB::table('responden_ikms')
            ->whereNotNull('kegiatan')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->select('kd_unsurikmpembinaan', DB::raw("ROUND(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"));

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }
        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

    // public function getIkm()
    // {
    //     $totalParameter = Pertanyaan::count();
    //     $totalNp = NilaiPersepsiIkm::count();

    //     if ($totalParameter === 0 || $totalNp === 0) {
    //         return null;
    //     }

    //     $konversi = 100 / $totalNp;
    //     $bobot = 1 / $totalParameter;

    //     $query = DB::table('responden_ikms')
    //         ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
    //         ->whereNotNull('respondens.kegiatan');

    //     if ($this->kegiatan) {
    //         $query->where('respondens.kegiatan', $this->kegiatan);
    //     }

    //     // Hitung total skor dan total responden unik
    //     $totalSkor = $query->sum('skor');
    //     // $totalResponden = $query->distinct('id_biodata')->count('id_biodata');
    //     $totalResponden = $query->distinct('responden_ikms.id_biodata')->count('responden_ikms.id_biodata');

    //     if ($totalResponden === 0) {
    //         return 0;
    //     }

    //     $ikm = ($totalSkor / $totalResponden) * $bobot * $konversi;

    //     return round($ikm, 2);
    // }

    public function getIkm()
    {
        $totalParameter = Pertanyaan::count();
        $totalNp = NilaiPersepsiIkm::count();

        if ($totalParameter === 0 || $totalNp === 0) {
            return null;
        }

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->select('kd_unsurikmpembinaan', DB::raw('AVG(skor) as rata_skor'));

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }

        $nrrPerParameter = $query->groupBy('kd_unsurikmpembinaan')->pluck('rata_skor');

        if ($nrrPerParameter->isEmpty()) {
            return 0;
        }

        $totalNilai = 0;
        foreach ($nrrPerParameter as $nrr) {
            $totalNilai += $nrr * $bobot;
        }

        $ikm = $totalNilai * $konversi;
        return round($ikm, 2);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Tombol untuk export per kegiatan
            Action::make('unduh_per_kegiatan')
                ->label('perKegiatan')
                ->color('success')
                ->icon('heroicon-m-arrow-down-tray')
                ->form([
                    Select::make('kegiatan')
                        ->label('Pilih Kegiatan')
                        ->options(Kegiatan::orderBy('n_kegiatan')->pluck('n_kegiatan', 'n_kegiatan'))
                        ->required()
                        ->searchable(),
                ])
                ->action(function (array $data) {
                    return redirect()->route('export.ikm-pembinaan', [
                        'kegiatanNama' => $data['kegiatan'],
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
