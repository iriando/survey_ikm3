<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Unsurikmpelayanan;
use App\Models\Pertanyaanikmpelayanan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PertanyaanikmpelayananResource\Pages;
use App\Filament\Resources\PertanyaanikmpelayananResource\RelationManagers;

class PertanyaanikmpelayananResource extends Resource
{
    protected static ?string $model = Pertanyaanikmpelayanan::class;

    protected static ?string $navigationGroup = 'IKM Pelayanan';

    protected static ?string $navigationParentItem = 'Unsur';

    protected static ?string $navigationLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unsur_id')
                    ->label('Nama Unsur')
                    ->options(function () {
                        return Unsurikmpelayanan::pluck('nama_unsur', 'id');
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $kdUnsur = Unsurikmpelayanan::find($state)?->kd_unsur;
                        $set('kd_unsur', $kdUnsur);
                    })
                    ->afterStateHydrated(function ($state, Set $set) {
                        // Saat form dimuat, set juga kd_unsur agar tetap sinkron
                        $kdUnsur = Unsurikmpelayanan::find($state)?->kd_unsur;
                        $set('kd_unsur', $kdUnsur);
                    }),

                Forms\Components\TextInput::make('kd_unsur')
                    ->label('Kode Unsur')
                    ->disabled()
                    ->dehydrated(false),

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
            'index' => Pages\ListPertanyaanikmpelayanans::route('/'),
            'create' => Pages\CreatePertanyaanikmpelayanan::route('/create'),
            'edit' => Pages\EditPertanyaanikmpelayanan::route('/{record}/edit'),
        ];
    }
}
