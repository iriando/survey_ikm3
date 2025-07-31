<?php

namespace App\Filament\Resources\RespondenResource\Pages;

use App\Filament\Resources\RespondenResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords;

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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
