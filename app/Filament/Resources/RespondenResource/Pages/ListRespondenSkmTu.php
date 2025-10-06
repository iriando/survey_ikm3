<?php

namespace App\Filament\Resources\RespondenResource\Pages;

use Filament\Tables;
use App\Models\Responden;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RespondenResource;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ListRespondenSkmTu extends ListRecords
{
    protected static string $resource = RespondenResource::class;

    public function getTitle(): string
    {
        return 'Responden SKM Tata Usaha';
    }

    public function getTableQuery(): ?Builder
    {
        return RespondenResource::getEloquentQuery()
            ->whereNotNull('j_layanantu');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('j_layanantu')
                    ->label('Jenis layanan')->searchable(),
                Tables\Columns\TextColumn::make('kritik_saran')
                    ->label('Kritik & Saran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->form([
                        DatePicker::make('start_date')->label('Tanggal Mulai'),
                        DatePicker::make('end_date')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Tables\Filters\SelectFilter::make('pekerjaan')
                    ->options([
                        'Pegawai Negeri Sipil' => 'PNS',
                        'Non ASN' => 'Non ASN',
                    ]),
                Tables\Filters\SelectFilter::make('usia')
                    ->options(
                        Responden::query()
                        ->select('usia')
                        ->distinct()
                        ->pluck('usia', 'usia')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(
                        Responden::query()
                        ->select('pendidikan')
                        ->distinct()
                        ->pluck('pendidikan', 'pendidikan')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('j_layanantu')
                    ->label('Jenis Pelayanan')
                    ->options(
                        Responden::query()
                        ->whereNotNull('j_layanantu')
                        ->select('j_layanantu')
                        ->distinct()
                        ->pluck('j_layanantu', 'j_layanantu')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
            ]);
    }
}
