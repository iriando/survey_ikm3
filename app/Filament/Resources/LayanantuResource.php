<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayanantuResource\Pages;
use App\Filament\Resources\LayanantuResource\RelationManagers;
use App\Models\Layanantu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LayanantuResource extends Resource
{
    protected static ?string $model = Layanantu::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'IKM Bagian Tata Usaha';

    protected static ?string $navigationLabel = 'Layanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subbag')
                    ->label('Input nama Subbagian')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('j_layanan')
                    ->label('Input jenis layanan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subbag')
                    ->label('Nama Subbagian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('j_layanan')
                    ->label('Jenis Layanan')
                    ->searchable(),
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
            'index' => Pages\ListLayanantus::route('/'),
            'create' => Pages\CreateLayanantu::route('/create'),
            'edit' => Pages\EditLayanantu::route('/{record}/edit'),
        ];
    }
}
