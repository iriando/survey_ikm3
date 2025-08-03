<?php

namespace App\Filament\Pages;

use Filament\Tables;
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
            ->whereNotNull('kegiatan');

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
            ->whereNotNull('kegiatan');

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
            ->select('kd_unsurikmpembinaan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"));

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }
        return $query->groupBy('kd_unsurikmpembinaan')->get();
    }

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
            ->whereNotNull('respondens.kegiatan');

        if ($this->kegiatan) {
            $query->where('respondens.kegiatan', $this->kegiatan);
        }

        // Hitung total skor dan total responden unik
        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('id_biodata')->count('id_biodata');

        if ($totalResponden === 0) {
            return 0;
        }

        $ikm = ($totalSkor / $totalResponden) * $bobot * $konversi;

        return round($ikm, 2);
    }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->query(function () {
    //             $query = RespondenIkm::query()->with('responden');

    //             if ($this->kegiatan) {
    //                 $query->whereHas('responden', function ($q) {
    //                     $q->where('kegiatan', $this->kegiatan);
    //                 });
    //             }

    //             return $query;
    //         })
    //         ->columns([
    //             TextColumn::make('responden.nama')
    //                 ->label('Nama Responden')
    //                 ->sortable()
    //                 ->searchable(),

    //             TextColumn::make('responden.usia')
    //                 ->label('Usia')
    //                 ->sortable(),

    //             BadgeColumn::make('responden.gender')
    //                 ->label('Gender')
    //                 ->colors([
    //                     'success' => 'Laki-laki',
    //                     'danger' => 'Perempuan',
    //                 ]),

    //             TextColumn::make('kd_unsurikmpembinaan')->label('Kode Unsur')->sortable(),
    //             TextColumn::make('skor')->label('Skor')->sortable(),
    //             TextColumn::make('created_at')->label('Tanggal dibuat')->sortable(),
    //         ])
    //         ->paginated(10);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduh_word')
                ->label('Unduh Word')
                ->url(fn () => $this->kegiatan
                    ? route('export.ikm-pembinaan', ['kegiatanNama' => $this->kegiatan])
                    : '#')
                ->disabled(fn () => !$this->kegiatan) // Disable tombol jika belum pilih kegiatan
                ->openUrlInNewTab()
                ->color('success')
                ->icon('heroicon-m-arrow-down-tray'),
        ];
    }

}
