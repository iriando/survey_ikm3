<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnsurikmpelayananResource\Pages;
use App\Models\Unsurikmpelayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
class UnsurikmpelayananResource extends Resource
{
    protected static ?string $model = Unsurikmpelayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'IKM Pelayanan';

    protected static ?string $navigationLabel = 'Unsur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_unsur')
                    ->label('Kode Unsur')
                    ->required()
                    ->maxLength(25),

                Forms\Components\TextInput::make('nama_unsur')
                    ->label('Nama Unsur')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Fieldset::make('Pertanyaan')
                    ->relationship('pertanyaan')
                    ->schema([
                        Forms\Components\Textarea::make('teks_pertanyaan')
                            ->label('Teks Pertanyaan')
                            ->required(),

                        Forms\Components\Repeater::make('pilihanJawabans')
                            ->label('Pilihan Jawaban')
                            ->relationship('pilihanJawabans')
                            ->schema([
                                Forms\Components\TextInput::make('teks_pilihan')
                                    ->label('Teks Pilihan')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('np')
                                            ->label('Nilai Persepsi')
                                            ->options(
                                                \App\Models\NilaiPersepsiIkm::orderByDesc('np')->pluck('np', 'np')
                                            )
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $data = \App\Models\NilaiPersepsiIkm::where('np', $state)->first();
                                                if ($data) {
                                                    $set('mutu', $data->mutu_pelayanan);
                                                    $set('bobot', round(($data->ni_terendah + $data->ni_tertinggi) / 2, 2));
                                                }
                                            })
                                            ->required(),

                                        Forms\Components\TextInput::make('mutu')
                                            ->label('Mutu')
                                            ->readOnly()
                                            ->required(),

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
                                                        return "{$data->ni_terendah} - {$data->ni_tertinggi}";
                                                    }
                                                }
                                                return null;
                                            }),
                                    ])
                                    ->columns(3), // <-- ini yang bikin sejajar dalam satu baris
                            ])
                            ->minItems(\App\Models\NilaiPersepsiIkm::count())
                            ->maxItems(\App\Models\NilaiPersepsiIkm::count())
                            ->defaultItems(\App\Models\NilaiPersepsiIkm::count())
                            ->grid(2)
                            ->reorderable(false),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kd_unsur')
                    ->label('Kode Unsur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_unsur')
                    ->label('Nama Unsur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
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
            'index' => Pages\ListUnsurikmpelayanans::route('/'),
            'create' => Pages\CreateUnsurikmpelayanan::route('/create'),
            'edit' => Pages\EditUnsurikmpelayanan::route('/{record}/edit'),
        ];
    }
}
