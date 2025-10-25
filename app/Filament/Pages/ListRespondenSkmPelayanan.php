<?php

namespace App\Filament\Pages;

use App\Models\Responden;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;

class ListRespondenSkmPelayanan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Pelayanan';
    protected static ?string $navigationLabel = 'Responden';
    protected static ?string $slug = 'respondenskmpelayanan';

    // ini wajib — tanpa ini, Filament tidak tahu tampilan yang digunakan
    protected static string $view = 'filament.pages.list-responden-skm-pelayanan';

    public function getTitle(): string
    {
        return 'Responden SKM Pelayanan';
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
                Tables\Columns\TextColumn::make('pekerjaan')->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('instansi')->label('Instansi'),
                Tables\Columns\TextColumn::make('j_layanan')->label('Jenis Layanan'),
                Tables\Columns\TextColumn::make('jawabansurvey.skor')->label('Skor'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Responden::query()
            ->whereNotNull('j_layanan')
            ->orderBy('nama', 'asc');
    }
}
