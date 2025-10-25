<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Pages\Page;
use App\Models\Responden;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ListRespondenSkmTu extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Bagian Tata Usaha';
    protected static ?string $navigationLabel = 'Responden';
    protected static ?string $slug = 'respondenskmtu';

    protected static string $view = 'filament.pages.list-responden-skm-tu';

    public function getTitle(): string
    {
        return 'Responden SKM Tata Usaha';
    }

    public function getQuery(): Builder
    {
        return Responden::getQuery()
            ->whereNotNull('j_layanantu');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('j_layanantu')
                    ->label('Jenis layanan')->searchable(),
                Tables\Columns\TextColumn::make('kritik_saran')
                    ->label('Kritik & Saran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Isi')->date('d/m/Y'),
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
                        Responden::query()
                        ->select('usia')
                        ->distinct()
                        ->pluck('usia', 'usia')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('pendidikan')
                    ->options(
                        Responden::query()
                        ->select('pendidikan')
                        ->distinct()
                        ->pluck('pendidikan', 'pendidikan')
                        ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('j_layanantu')
                    ->label('Jenis Pelayanan')
                    ->options(
                        Responden::query()
                        ->whereNotNull('j_layanantu')
                        ->select('j_layanantu')
                        ->distinct()
                        ->pluck('j_layanantu', 'j_layanantu')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()
            ]);
    }
}
