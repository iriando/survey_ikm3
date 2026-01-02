<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PertanyaanikmpembinaanResource\Pages;
use App\Filament\Resources\PertanyaanikmpembinaanResource\RelationManagers;
use App\Models\Pertanyaanikmpembinaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PertanyaanikmpembinaanResource extends Resource
{
    protected static ?string $model = Pertanyaanikmpembinaan::class;

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
                    ->disabled()
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

                Forms\Components\Textarea::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->required()
                    ->columnSpanFull(),
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
            'index' => Pages\ListPertanyaanikmpembinaans::route('/'),
            'create' => Pages\CreatePertanyaanikmpembinaan::route('/create'),
            'edit' => Pages\EditPertanyaanikmpembinaan::route('/{record}/edit'),
        ];
    }
}
