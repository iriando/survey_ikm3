<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiPersepsiIkmResource\Pages;
use App\Models\NilaiPersepsiIkm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NilaiPersepsiIkmResource extends Resource
{
    protected static ?string $model = NilaiPersepsiIkm::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Nilai Persepsi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('np')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ni_terendah')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ni_tertinggi')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nik_terendah')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('nik_tertinggi')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('mutu_pelayanan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kinerja')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('np')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ni_terendah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ni_tertinggi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik_terendah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nik_tertinggi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mutu_pelayanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kinerja')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListNilaiPersepsiIkms::route('/'),
            'create' => Pages\CreateNilaiPersepsiIkm::route('/create'),
            'edit' => Pages\EditNilaiPersepsiIkm::route('/{record}/edit'),
        ];
    }
}
