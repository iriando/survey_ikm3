<?php

namespace App\Filament\Resources\PilihanJawabanResource\Pages;

use App\Filament\Resources\PilihanJawabanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilihanJawabans extends ListRecords
{
    protected static string $resource = PilihanJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Pilihan Jawaban IKM Pembinaan';
    }
}
