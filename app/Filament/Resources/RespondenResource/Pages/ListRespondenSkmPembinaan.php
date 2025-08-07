<?php

namespace App\Filament\Resources\RespondenResource\Pages;

use App\Filament\Resources\RespondenResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Table;

class ListRespondenSkmPembinaan extends ListRecords
{
    protected static string $resource = RespondenResource::class;

    public function getTitle(): string
    {
        return 'Responden SKM Pembinaan';
    }

    public function getTableQuery(): ?Builder
    {
        return RespondenResource::getEloquentQuery()
            ->whereNotNull('kegiatan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('usia')->label('Usia'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('nohp')->label('No. HP'),
                Tables\Columns\TextColumn::make('pendidikan')->label('Pendidikan'),
                Tables\Columns\TextColumn::make('pekerjaan')->label('Pekerjaan')->searchable(),
                Tables\Columns\TextColumn::make('instansi')->label('Instansi')->searchable(),
                Tables\Columns\TextColumn::make('kegiatan')->label('Kegiatan')->searchable(),
                Tables\Columns\TextColumn::make('kritik_saran')->label('Kritik & Saran'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
