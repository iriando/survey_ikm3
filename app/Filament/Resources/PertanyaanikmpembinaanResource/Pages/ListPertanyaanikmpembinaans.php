<?php

namespace App\Filament\Resources\PertanyaanikmpembinaanResource\Pages;

use App\Filament\Resources\PertanyaanikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPertanyaanikmpembinaans extends ListRecords
{
    protected static string $resource = PertanyaanikmpembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Pertanyaan IKM Pembinaan';
    }
}
