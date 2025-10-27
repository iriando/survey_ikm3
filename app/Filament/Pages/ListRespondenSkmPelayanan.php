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

class ListRespondenSkmPelayanan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'IKM Pelayanan';
    protected static ?string $navigationLabel = 'Responden';
    protected static ?string $slug = 'respondenskmpelayanan';

    // ini wajib — tanpa ini, Filament tidak tahu tampilan yang digunakan
    protected static string $view = 'filament.pages.list-responden-skm-pelayanan';

    public function getTitle(): string
    {
        return 'Responden SKM Pelayanan';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
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
                Tables\Filters\SelectFilter::make('j_layanan')
                    ->label('Jenis Pelayanan')
                    ->options(
                        Responden::query()
                        ->whereNotNull('j_layanan')
                        ->select('j_layanan')
                        ->distinct()
                        ->pluck('j_layanan', 'j_layanan')
                        ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Responden::query()
            ->whereNotNull('j_layanan')
            ->orderBy('nama', 'asc');
    }
}
