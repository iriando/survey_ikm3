<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondenPelayananResource\Pages;
use App\Filament\Resources\RespondenPelayananResource\RelationManagers;
use App\Models\RespondenPelayanan;
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

class RespondenPelayananResource extends Resource
{
    protected static ?string $model = RespondenPelayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Pelayanan';
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
                Tables\Columns\TextColumn::make('pekerjaan')->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('instansi')->label('Instansi'),
                Tables\Columns\TextColumn::make('j_layanan')->label('Jenis Layanan'),
                Tables\Columns\TextColumn::make('jawabansurvey.skor')->label('Skor'),
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
                Tables\Filters\SelectFilter::make('pekerjaan')
                    ->options([
                        'Pegawai Negeri Sipil' => 'PNS',
                        'Non ASN' => 'Non ASN',
                    ]),
                Tables\Filters\SelectFilter::make('usia')
                    ->options(
                        RespondenPelayanan::query()
                        ->select('usia')
                        ->distinct()
                        ->pluck('usia', 'usia')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(
                        RespondenPelayanan::query()
                        ->select('pendidikan')
                        ->distinct()
                        ->pluck('pendidikan', 'pendidikan')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('j_layanan')
                    ->label('Jenis Pelayanan')
                    ->options(
                        RespondenPelayanan::query()
                        ->whereNotNull('j_layanan')
                        ->select('j_layanan')
                        ->distinct()
                        ->pluck('j_layanan', 'j_layanan')
                        ->toArray()
                    ),
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
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRespondenPelayanans::route('/'),
            'create' => Pages\CreateRespondenPelayanan::route('/create'),
            'edit' => Pages\EditRespondenPelayanan::route('/{record}/edit'),
        ];
    }
}
