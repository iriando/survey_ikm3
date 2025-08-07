<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Responden;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RespondenResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RespondenResource\RelationManagers;

class RespondenResource extends Resource
{
    protected static ?string $model = Responden::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('nama')
                //     ->label('Nama')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('usia')
                //     ->label('Usia'),
                // Tables\Columns\TextColumn::make('gender')
                //     ->label('Jenis Kelamin'),
                // Tables\Columns\TextColumn::make('nohp')
                //     ->label('No. HP'),
                // Tables\Columns\TextColumn::make('pendidikan')
                //     ->label('Pendidikan'),
                // Tables\Columns\TextColumn::make('pekerjaan')
                //     ->label('Pekerjaan')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('instansi')
                //     ->label('Instansi')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('kegiatan')
                //     ->label('Kegiatan')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('kritik_saran')
                //     ->label('Kritik & Saran'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Tanggal isi')
                //     ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRespondens::route('/'),
            'respondenskmpembinaan' => Pages\ListRespondenSkmPembinaan::route('/respondenskmpembinaan'),
            'respondenskmpelayanan' => Pages\ListRespondenSkmPelayanan::route('/respondenskmpelayanan'),
            // 'create' => Pages\CreateResponden::route('/create'),
            // 'edit' => Pages\EditResponden::route('/{record}/edit'),
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Responden')
                ->url(static::getUrl('respondenskmpembinaan'))
                ->icon('heroicon-o-user')
                ->group('IKM Pembinaan'),
            NavigationItem::make('Responden')
                ->url(static::getUrl('respondenskmpelayanan'))
                ->icon('heroicon-o-user')
                ->group('IKM Pelayanan'),
        ];
    }

}
