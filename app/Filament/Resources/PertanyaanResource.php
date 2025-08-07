<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Unsur;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pertanyaan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PertanyaanResource\Pages;
use App\Filament\Resources\PertanyaanResource\RelationManagers;

class PertanyaanResource extends Resource
{
    protected static ?string $model = Pertanyaan::class;

    protected static ?string $navigationGroup = 'IKM Pembinaan';

    protected static ?string $navigationParentItem = 'Unsur';

    protected static ?string $navigationLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unsur_id')
                    ->label('Nama Unsur')
                    ->options(function () {
                        return Unsur::pluck('nama_unsur', 'id');
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $kdUnsur = Unsur::find($state)?->kd_unsur;
                        $set('kd_unsur', $kdUnsur);
                    })
                    ->afterStateHydrated(function ($state, Set $set) {
                        // Saat form dimuat, set juga kd_unsur agar tetap sinkron
                        $kdUnsur = Unsur::find($state)?->kd_unsur;
                        $set('kd_unsur', $kdUnsur);
                    }),

                Forms\Components\TextInput::make('kd_unsur')
                    ->label('Kode Unsur')
                    ->disabled()
                    ->dehydrated(false), // agar tidak disimpan ke DB
                    // atau kamu bisa hidden() jika hanya untuk internal

                Forms\Components\TextArea::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unsur.kd_unsur')
                    ->label('Kode Unsur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
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
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListPertanyaans::route('/'),
            'create' => Pages\CreatePertanyaan::route('/create'),
            'edit' => Pages\EditPertanyaan::route('/{record}/edit'),
        ];
    }
}
