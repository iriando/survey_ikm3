<?php

namespace App\Filament\Resources\PilihanJawabanikmpembinaanResource\Pages;

use App\Filament\Resources\PilihanJawabanikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilihanJawabanikmpembinaans extends ListRecords
{
    protected static string $resource = PilihanJawabanikmpembinaanResource::class;

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
