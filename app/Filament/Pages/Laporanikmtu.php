<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\RespondenTu;
use Filament\Tables\Table;
use App\Models\RespondenIkmTu;
use Filament\Actions\Action;
use App\Models\Pertanyaanikmtu;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Laporanikmtu extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporanikmtu';
    protected static ?string $navigationGroup = 'IKM Bagian Tata Usaha';
    protected static ?string $title = 'Laporan IKM Bagian Tata usaha';

    public ?string $layanan = null;
    public ?string $tanggalMulai = null;
    public ?string $tanggalAkhir = null;
    public ?string $jenisLayanan = null;

    public function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    DatePicker::make('tanggalMulai')
                        ->label('Tanggal Mulai')
                        ->reactive()
                        ->afterStateUpdated(fn () => $this->resetTable()),

                    DatePicker::make('tanggalAkhir')
                        ->label('Tanggal Akhir')
                        ->reactive()
                        ->after('tanggalMulai')
                        ->afterStateUpdated(fn () => $this->resetTable()),

                    Select::make('jenisLayanan')
                        ->label('Jenis Layanan')
                        ->options(
                            RespondenTu::query()
                                ->whereNotNull('layanan_tu')
                                ->distinct()
                                ->pluck('layanan_tu', 'layanan_tu')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn () => $this->resetTable()),
                ]),
        ];
    }

    public function getDataResponden()
    {
        $pertanyaan = Pertanyaanikmtu::with('unsur')->get();

        $selects = [
            'respondentus.id AS responden_id',
            'respondentus.nama AS nama_responden',
            'DATE(responden_ikm_tus.updated_at) AS tanggal',
        ];

        foreach ($pertanyaan as $p) {
            $kd = $p->unsur->kd_unsur;
            $selects[] = "MAX(CASE WHEN kd_unsurikmtu = '{$kd}' THEN skor END) AS `{$kd}`";
        }

        return DB::table('responden_ikm_tus')
            ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
            ->whereNotNull('respondentus.layanan_tu')
            ->when($this->jenisLayanan, fn($q) =>
                $q->where('respondentus.layanan_tu', $this->jenisLayanan)
            )
            ->selectRaw(implode(', ', $selects))
            ->groupBy(
                'respondentus.id',
                'respondentus.nama',
                DB::raw('DATE(responden_ikm_tus.updated_at)')
            )
            ->orderBy('tanggal')
            ->get();
    }
    // public function getDataResponden()
    // {
    //     $pertanyaan = Pertanyaanikmtu::all();

    //     $query = DB::table('responden_ikm_tus')
    //         ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
    //         ->whereNotNull('respondentus.layanan_tu')
    //         ->selectRaw('respondentus.nama AS nama_responden, ' . collect($pertanyaan)->map(function ($p) {
    //             return "MAX(CASE WHEN kd_unsurikmtu = '{$p->unsur->kd_unsur}' THEN skor END) AS `{$p->unsur->kd_unsur}`";
    //         })->implode(', ') . ', DATE(responden_ikm_tus.updated_at) as tanggal');

    //     if ($this->tanggalMulai) {
    //         $query->whereDate('responden_ikm_tus.updated_at', '>=', $this->tanggalMulai);
    //     }
    //     if ($this->tanggalAkhir) {
    //         $query->whereDate('responden_ikm_tus.updated_at', '<=', $this->tanggalAkhir);
    //     }
    //     if ($this->jenisLayanan) {
    //         $query->where('respondentus.layanan_tu', $this->jenisLayanan);
    //     }

    //     return $query->groupBy('respondentus.nama', 'tanggal')->get();
    // }

    public function getPertanyaan()
    {
        return Pertanyaanikmtu::all();
    }

    public function getTotalPerParameter()
    {
        $query = DB::table('responden_ikm_tus')
            ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
            ->whereNotNull('respondentus.layanan_tu');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikm_tus.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikm_tus.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondentus.layanan_tu', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmtu', DB::raw('SUM(skor) as total_skor'))
            ->groupBy('kd_unsurikmtu')
            ->get();
    }

    public function getAveragePerParameter()
    {
        $query = DB::table('responden_ikm_tus')
            ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
            ->whereNotNull('layanan_tu');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikm_tus.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikm_tus.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondentus.layanan_tu', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmtu', DB::raw('FORMAT(AVG(skor), 2) as avg_skor'))
            ->groupBy('kd_unsurikmtu')
            ->get();
    }

    public function getSkmPerParameter()
    {
        $totalParameter = Pertanyaanikmtu::count();
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikm_tus')
            ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
            ->whereNotNull('layanan_tu');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikm_tus.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikm_tus.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondentus.layanan_tu', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmtu', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->groupBy('kd_unsurikmtu')
            ->get();
    }

    public function getIkm()
    {
        $totalParameter = Pertanyaanikmtu::count();
        $totalNp = NilaiPersepsiIkm::count();

        if ($totalParameter === 0 || $totalNp === 0) {
            return null;
        }

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikm_tus')
            ->join('respondentus', 'responden_ikm_tus.id_biodata', '=', 'respondentus.id')
            ->whereNotNull('respondentus.layanan_tu');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikm_tus.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikm_tus.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondentus.layanan_tu', $this->jenisLayanan);
        }

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('responden_ikm_tus.id_biodata')->count('responden_ikm_tus.id_biodata');

        if ($totalResponden === 0) {
            return 0;
        }

        $ikm = ($totalSkor / $totalResponden) * $bobot * $konversi;
        return round($ikm, 2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = RespondenIkmTu::query()->with('responden');

                if ($this->tanggalMulai) {
                    $query->whereDate('updated_at', '>=', $this->tanggalMulai);
                }
                if ($this->tanggalAkhir) {
                    $query->whereDate('updated_at', '<=', $this->tanggalAkhir);
                }
                if ($this->jenisLayanan) {
                    $query->whereHas('responden', function ($q) {
                        $q->where('layanan_tu', $this->jenisLayanan);
                    });
                }

                return $query;
            })
            ->columns([
                TextColumn::make('respondentus.nama')
                    ->label('Nama Responden')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kd_unsur')->label('Kode Unsur')->sortable(),
                TextColumn::make('skor')->label('Skor')->sortable(),
                TextColumn::make('created_at')->label('Tanggal dibuat')->sortable(),
            ])
            ->paginated(10);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('Unduh Word')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(function () {
                    return route('export.ikm.tu', [
                        'tanggalMulai' => $this->tanggalMulai,
                        'tanggalAkhir' => $this->tanggalAkhir,
                        'jenisLayanan' => $this->jenisLayanan,
                    ]);
                }, shouldOpenInNewTab: true)
                ->visible(fn () => $this->tanggalMulai && $this->tanggalAkhir),
        ];
    }
}
