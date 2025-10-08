<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\NilaiPersepsiIkm;
use Filament\Resources\Resource;
use App\Models\Pertanyaanikmpelayanan;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Pilihan_jawabanikmpelayanan;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PilihanJawabanikmpelayananResource\Pages;
use App\Filament\Resources\PilihanJawabanikmpelayananResource\RelationManagers;

class PilihanJawabanikmpelayananResource extends Resource
{
    protected static ?string $model = Pilihan_jawabanikmpelayanan::class;

    protected static ?string $navigationGroup = 'IKM Pelayanan';

    protected static ?string $navigationParentItem = 'Unsur';

    protected static ?string $navigationLabel = 'Pilihan Jawaban';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pertanyaan_id')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->options(Pertanyaanikmpelayanan::all()->pluck('teks_pertanyaan', 'id'))
                    ->required()
                    ->disabled(),
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
                        $mutu = \App\Models\NilaiPersepsiIkm::where('np', $state)->first()?->mutu_pelayanan;
                        $ni_terendah = \App\Models\NilaiPersepsiIkm::where('np', $state)->first()?->ni_terendah;
                        $ni_tertinggi = \App\Models\NilaiPersepsiIkm::where('np', $state)->first()?->ni_tertinggi;

                        if ($mutu) {
                            $set('mutu', $mutu);
                        }
                        if ($ni_terendah !== null && $ni_tertinggi !== null) {
                            $set('bobot', round(($ni_terendah + $ni_tertinggi) / 2, 2));
                        }
                    })
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('mutu')
                    ->label('Mutu')
                    ->readOnly()
                    ->required()
                    ->disabled(),

                Forms\Components\TextInput::make('bobot')
                    ->label('Bobot')
                    ->numeric()
                    ->step(0.01)
                    ->reactive()
                    ->required()
                    ->minValue(function (callable $get) {
                        $np = $get('np');
                        if ($np) {
                            return \App\Models\NilaiPersepsiIkm::where('np', $np)->first()?->ni_terendah ?? 0;
                        }
                        return 0;
                    })
                    ->maxValue(function (callable $get) {
                        $np = $get('np');
                            if ($np) {
                                return \App\Models\NilaiPersepsiIkm::where('np', $np)->first()?->ni_tertinggi ?? 5;
                            }
                            return 5;
                        })
                    ->hint(function (callable $get) {
                        $np = $get('np');
                        if ($np) {
                            $data = \App\Models\NilaiPersepsiIkm::where('np', $np)->first();
                            if ($data) {
                                return "Rentang: {$data->ni_terendah} - {$data->ni_tertinggi}";
                            }
                        }
                        return null;
                    }),
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
                Tables\Columns\TextColumn::make('bobot')
                    ->label('Bobot jawaban'),
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
            'index' => Pages\ListPilihanJawabanikmpelayanans::route('/'),
            'create' => Pages\CreatePilihanJawabanikmpelayanan::route('/create'),
            'edit' => Pages\EditPilihanJawabanikmpelayanan::route('/{record}/edit'),
        ];
    }
}
