<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnsurikmtuResource\Pages;
use App\Filament\Resources\UnsurikmtuResource\RelationManagers;
use App\Models\Unsurikmtu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnsurikmtuResource extends Resource
{
    protected static ?string $model = Unsurikmtu::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'IKM Bagian Tata Usaha';

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
                                    ->required(),

                                Forms\Components\Select::make('np')
                                    ->label('Nilai Persepsi (NP)')
                                    ->options(
                                        \App\Models\NilaiPersepsiIkm::orderByDesc('np')->pluck('np', 'np')
                                    )
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $mutu = \App\Models\NilaiPersepsiIkm::where('np', $state)->first()?->mutu_pelayanan;
                                        if ($mutu) {
                                            $set('mutu', $mutu);
                                        }
                                    })
                                    ->required(),

                                Forms\Components\TextInput::make('mutu')
                                    ->label('Mutu')
                                    ->readOnly()
                                    ->required(),
                            ])
                            ->minItems(
                                \App\Models\NilaiPersepsiIkm::count()
                            )
                            ->maxItems(
                                \App\Models\NilaiPersepsiIkm::count()
                            )
                            ->defaultItems(
                                \App\Models\NilaiPersepsiIkm::count()
                            )
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
            'index' => Pages\ListUnsurikmtus::route('/'),
            'create' => Pages\CreateUnsurikmtu::route('/create'),
            'edit' => Pages\EditUnsurikmtu::route('/{record}/edit'),
        ];
    }
}
