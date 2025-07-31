<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Pilihan_jawaban;
use App\Models\Pertanyaan;
use App\Models\NilaiPersepsiIkm;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PilihanJawabanResource\Pages;
use App\Filament\Resources\PilihanJawabanResource\RelationManagers;

class PilihanJawabanResource extends Resource
{
    protected static ?string $model = Pilihan_jawaban::class;

    protected static ?string $navigationGroup = 'IKM Pembinaan';

    protected static ?string $navigationParentItem = 'Unsur';

    protected static ?string $navigationLabel = 'Pilihan Jawaban';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pertanyaan_id')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->options(Pertanyaan::all()->pluck('teks_pertanyaan', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('teks_pilihan')
                    ->label('Teks Pilihan')
                    ->required(),
                Forms\Components\Select::make('np')
                    ->label('Nilai Persepsi (NP)')
                    ->options(
                        fn () => NilaiPersepsiIkm::orderByDesc('np')
                            ->pluck('np', 'np')
                    )
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $mutu = NilaiPersepsiIkm::where('np', $state)->first()?->mutu_pelayanan;
                        if ($mutu) {
                            $set('mutu', $mutu);
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('mutu')
                    ->label('Mutu')
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan.unsur.kd_unsur')
                    ->label('Kode Unsur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teks_pilihan')
                    ->label('Pilihan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('np')
                    ->label('Nilai Persepsi'),
                Tables\Columns\TextColumn::make('mutu')
                    ->label('Mutu'),
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
            'index' => Pages\ListPilihanJawabans::route('/'),
            'create' => Pages\CreatePilihanJawaban::route('/create'),
            'edit' => Pages\EditPilihanJawaban::route('/{record}/edit'),
        ];
    }
}
