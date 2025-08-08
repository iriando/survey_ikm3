<?php

namespace App\Filament\Resources\RespondenResource\Pages;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RespondenResource;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ListRespondenSkmPelayanan extends ListRecords
{
    protected static string $resource = RespondenResource::class;

    public function getTitle(): string
    {
        return 'Responden SKM Pelayanan';
    }

    public function getTableQuery(): ?Builder
    {
        return RespondenResource::getEloquentQuery()
            ->whereNotNull('j_layanan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('usia')
                    ->label('Usia'),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('nohp')
                    ->label('No. HP'),
                Tables\Columns\TextColumn::make('pendidikan')
                    ->label('Pendidikan'),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->label('Pekerjaan')->searchable(),
                Tables\Columns\TextColumn::make('instansi')
                    ->label('Instansi')->searchable(),
                Tables\Columns\TextColumn::make('j_layanan')
                    ->label('Jenis layanan')->searchable(),
                Tables\Columns\TextColumn::make('kritik_saran')
                    ->label('Kritik & Saran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->filters([
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
            //     Tables\Filters\SelectFilter::make('pendidikan')
            //         ->options([
            //             'Pegawai Negeri Sipil' => 'PNS',
            //             'Non ASN' => 'Non ASN',
            //         ]),
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
