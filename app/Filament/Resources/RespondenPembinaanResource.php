<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondenPembinaanResource\Pages;
use App\Filament\Resources\RespondenPembinaanResource\RelationManagers;
use App\Models\RespondenPembinaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class RespondenPembinaanResource extends Resource
{
    protected static ?string $model = RespondenPembinaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Pembinaan';
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
                Tables\Columns\TextColumn::make('instansi')->label('Instansi'),
                Tables\Columns\TextColumn::make('kegiatan')->label('Kegiatan'),
                Tables\Columns\TextColumn::make('jawabansurvey.skor')->label('Skor'),
                Tables\Columns\TextColumn::make('kritik_saran')->label('Kritik / Saran'),
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
                        RespondenPembinaan::query()
                        ->select('usia')
                        ->distinct()
                        ->pluck('usia', 'usia')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(
                        RespondenPembinaan::query()
                        ->select('pendidikan')
                        ->distinct()
                        ->pluck('pendidikan', 'pendidikan')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('kegiatan')
                    ->label('Nama Kegiatan')
                    ->options(
                        RespondenPembinaan::query()
                        ->whereNotNull('kegiatan')
                        ->select('kegiatan')
                        ->distinct()
                        ->pluck('kegiatan', 'kegiatan')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make('export_all')
                    ->label('Export Semua (Filtered)')
                    ->exports([
                        ExcelExport::make()
                            ->withFilename(fn ($livewire, $livewireClass, $resource, $model, $recordIds, $query) => 'responden_' . now()->format('Ymd_His'))
                            ->fromTable() // <-- ambil seluruh hasil query & filter aktif
                            ->withColumns([
                                Column::make('nama')->heading('Nama'),
                                Column::make('nohp')->heading('No. HP'),
                                Column::make('gender')->heading('Jenis Kelamin'),
                                Column::make('pendidikan')->heading('Pendidikan'),
                                Column::make('jabatan')->heading('Jabatan'),
                                Column::make('instansi')->heading('Instansi'),
                                Column::make('kegiatan')->heading('Kegiatan'),
                                Column::make('jawabansurvey.skor')->heading('Skor'),
                                Column::make('created_at')->heading('Tanggal Isi'),
                            ]),
                    ]),
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
            'index' => Pages\ListRespondenPembinaans::route('/'),
            'create' => Pages\CreateRespondenPembinaan::route('/create'),
            'edit' => Pages\EditRespondenPembinaan::route('/{record}/edit'),
        ];
    }
}
