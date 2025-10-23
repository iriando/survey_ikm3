<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\Responden;
use Filament\Tables\Table;
use App\Models\RespondenIkm;
use Filament\Actions\Action;
use App\Models\NilaiPersepsiIkm;
use Illuminate\Support\Facades\DB;
use App\Models\Pertanyaanikmpelayanan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Laporanikmpelayanan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporanikmpelayanan';
    protected static ?string $navigationGroup = 'IKM Pelayanan';
    protected static ?string $title = 'Laporan IKM Pelayanan';

    public ?string $layanan = null;
    public ?string $tanggalMulai = null;
    public ?string $tanggalAkhir = null;
    public ?string $jenisLayanan = null; // tambahan

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
                            Responden::query()
                                ->whereNotNull('j_layanan')
                                ->distinct()
                                ->pluck('j_layanan', 'j_layanan')
                        )
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(fn () => $this->resetTable()),
                ]),
        ];
    }

    public function getDataResponden()
    {
        $pertanyaan = Pertanyaanikmpelayanan::all();

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan')
            ->selectRaw('respondens.nama AS nama_responden, ' . collect($pertanyaan)->map(function ($p) {
                return "MAX(CASE WHEN kd_unsurikmpelayanan = '{$p->unsur->kd_unsur}' THEN skor END) AS `{$p->unsur->kd_unsur}`";
            })->implode(', ') . ', DATE(responden_ikms.updated_at) as tanggal');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikms.updated_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikms.updated_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondens.j_layanan', $this->jenisLayanan);
        }

        return $query->groupBy('respondens.nama', 'tanggal')->get();
    }

    public function getPertanyaan()
    {
        return Pertanyaanikmpelayanan::all();
    }

    public function getGenderCount()
    {
        $query = Responden::query()
            ->whereNotNull('j_layanan')
            ->whereHas('jawabansurvey');;

        if ($this->tanggalMulai) {
            $query->whereDate('created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('j_layanan', $this->jenisLayanan);
        }

        return $query->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
    }

    public function getPendidikanCount()
    {
        $query = Responden::query()
            ->whereNotNull('j_layanan')
            ->whereHas('jawabansurvey');

        if ($this->tanggalMulai) {
            $query->whereDate('created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('j_layanan', $this->jenisLayanan);
        }

        return $query->select('pendidikan', DB::raw('count(*) as total'))
            ->groupBy('pendidikan')
            ->orderBy('pendidikan', 'asc')
            ->get();
    }

    public function getTotalPerParameter()
    {
        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikms.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikms.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondens.j_layanan', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmpelayanan', DB::raw('SUM(skor) as total_skor'))
            ->groupBy('kd_unsurikmpelayanan')
            ->get();
    }

    public function getAveragePerParameter()
    {
        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('j_layanan');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikms.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikms.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondens.j_layanan', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmpelayanan', DB::raw('FORMAT(AVG(skor), 2) as avg_skor'))
            ->groupBy('kd_unsurikmpelayanan')
            ->get();
    }

    public function getSkmPerParameter()
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('j_layanan');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikms.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikms.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondens.j_layanan', $this->jenisLayanan);
        }

        return $query
            ->select('kd_unsurikmpelayanan', DB::raw("FORMAT(SUM(skor) / COUNT(skor) * $bobot, 2) as skm"))
            ->groupBy('kd_unsurikmpelayanan')
            ->get();
    }

    public function getIkm()
    {
        $totalParameter = Pertanyaanikmpelayanan::count();
        $totalNp = NilaiPersepsiIkm::count();

        if ($totalParameter === 0 || $totalNp === 0) {
            return null;
        }

        $konversi = 100 / $totalNp;
        $bobot = 1 / $totalParameter;

        $query = DB::table('responden_ikms')
            ->join('respondens', 'responden_ikms.id_biodata', '=', 'respondens.id')
            ->whereNotNull('respondens.j_layanan');

        if ($this->tanggalMulai) {
            $query->whereDate('responden_ikms.created_at', '>=', $this->tanggalMulai);
        }
        if ($this->tanggalAkhir) {
            $query->whereDate('responden_ikms.created_at', '<=', $this->tanggalAkhir);
        }
        if ($this->jenisLayanan) {
            $query->where('respondens.j_layanan', $this->jenisLayanan);
        }

        $totalSkor = $query->sum('skor');
        $totalResponden = $query->distinct('responden_ikms.id_biodata')->count('responden_ikms.id_biodata');

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
                $query = RespondenIkm::query()->with('responden');

                if ($this->tanggalMulai) {
                    $query->whereDate('updated_at', '>=', $this->tanggalMulai);
                }
                if ($this->tanggalAkhir) {
                    $query->whereDate('updated_at', '<=', $this->tanggalAkhir);
                }
                if ($this->jenisLayanan) {
                    $query->whereHas('responden', function ($q) {
                        $q->where('j_layanan', $this->jenisLayanan);
                    });
                }

                return $query;
            })
            ->columns([
                TextColumn::make('responden.nama')
                    ->label('Nama Responden')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('responden.usia')
                    ->label('Usia')
                    ->sortable(),

                BadgeColumn::make('responden.gender')
                    ->label('Gender')
                    ->colors([
                        'success' => 'Laki-laki',
                        'danger' => 'Perempuan',
                    ]),

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
                    return route('export.ikm.pelayanan', [
                        'tanggalMulai' => $this->tanggalMulai,
                        'tanggalAkhir' => $this->tanggalAkhir,
                        'jenisLayanan' => $this->jenisLayanan,
                    ]);
                }, shouldOpenInNewTab: true)
                ->visible(fn () => $this->tanggalMulai && $this->tanggalAkhir),
        ];
    }
}
