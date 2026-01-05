<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondenTuResource\Pages;
use App\Filament\Resources\RespondenTuResource\RelationManagers;
use App\Models\RespondenTu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class RespondenTuResource extends Resource
{
    protected static ?string $model = RespondenTu::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Bagian Tata Usaha';
    protected static ?string $navigationLabel = 'Responden';

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
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('usia')->label('Usia'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('nohp')->label('No. HP'),
                Tables\Columns\TextColumn::make('pendidikan')->label('Pendidikan'),
                Tables\Columns\TextColumn::make('layanan_tu')->label('Jenis Layanan'),
                Tables\Columns\TextColumn::make('jawabansurvey.skor')->label('Skor'),
                Tables\Columns\TextColumn::make('kritik_saran')->label('Kritik & Saran'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Isi')->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->form([
                        DatePicker::make('start_date')->label('Tanggal Mulai'),
                        DatePicker::make('end_date')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Tables\Filters\SelectFilter::make('usia')
                    ->options(
                        RespondenTu::query()
                        ->select('usia')
                        ->distinct()
                        ->pluck('usia', 'usia')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(
                        RespondenTu::query()
                        ->select('pendidikan')
                        ->distinct()
                        ->pluck('pendidikan', 'pendidikan')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('layanan_tu')
                    ->label('Jenis Pelayanan')
                    ->options(
                        RespondenTu::query()
                        ->whereNotNull('layanan_tu')
                        ->select('layanan_tu')
                        ->distinct()
                        ->pluck('layanan_tu', 'layanan_tu')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
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
            'index' => Pages\ListRespondenTus::route('/'),
            'create' => Pages\CreateRespondenTu::route('/create'),
            'edit' => Pages\EditRespondenTu::route('/{record}/edit'),
        ];
    }
}
