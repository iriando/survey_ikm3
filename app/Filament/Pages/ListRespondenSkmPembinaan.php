<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\Responden;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Support\Facades\Cache;

class ListRespondenSkmPembinaan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Pembinaan';
    protected static ?string $navigationLabel = 'Responden';
    protected static ?string $slug = 'respondenskmpembinaan';
    protected static string $view = 'filament.pages.list-responden-skm-pembinaan';

    public function getTitle(): string
    {
        return 'Responden SKM Pembinaan';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('usia')->label('Usia'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('nohp')->label('No. HP'),
                Tables\Columns\TextColumn::make('pendidikan')->label('Pendidikan'),
                Tables\Columns\TextColumn::make('jabatan')->label('Jabatan')->searchable(),
                Tables\Columns\TextColumn::make('instansi')->label('Instansi')->searchable(),
                Tables\Columns\TextColumn::make('kegiatan')->label('Kegiatan')->searchable(),
                Tables\Columns\TextColumn::make('jawabansurvey.skor')->label('Skor'),
                Tables\Columns\TextColumn::make('kritik_saran')->label('Kritik & Saran')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->form([
                        DatePicker::make('start_date')->label('Tanggal Mulai'),
                        DatePicker::make('end_date')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Tables\Filters\SelectFilter::make('usia')
                    ->options(fn () => Cache::remember('filter_usia', 3600, function () {
                        return Responden::query()
                            ->select('usia')
                            ->distinct()
                            ->orderBy('usia')
                            ->limit(100)
                            ->pluck('usia', 'usia')
                            ->toArray();
                    })),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(fn () => Cache::remember('filter_pendidikan', 3600, function () {
                        return Responden::query()
                            ->select('pendidikan')
                            ->distinct()
                            ->orderBy('pendidikan')
                            ->pluck('pendidikan', 'pendidikan')
                            ->toArray();
                    })),
                Tables\Filters\SelectFilter::make('kegiatan')
                    ->label('Kegiatan')
                    ->options(fn () => Cache::remember('filter_kegiatan', 3600, function () {
                        return Responden::query()
                            ->whereNotNull('kegiatan')
                            ->select('kegiatan')
                            ->distinct()
                            ->orderBy('kegiatan')
                            ->pluck('kegiatan', 'kegiatan')
                            ->toArray();
                    })),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ])
            ->paginationPageOptions([10, 20, 50]);
    }

    protected function getQuery(): Builder
    {
        return Responden::query()
            ->with('jawabansurvey')
            ->whereNotNull('kegiatan')
            ->orderBy('nama', 'asc');
    }
}
