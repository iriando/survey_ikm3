<?php

namespace App\Filament\Resources\PertanyaanikmpelayananResource\Pages;

use App\Filament\Resources\PertanyaanikmpelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPertanyaanikmpelayanans extends ListRecords
{
    protected static string $resource = PertanyaanikmpelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Pertanyaan IKM Pelayanan';
    }
}
