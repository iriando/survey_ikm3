<?php

namespace App\Filament\Resources\RespondenPembinaanResource\Pages;

use App\Filament\Resources\RespondenPembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRespondenPembinaans extends ListRecords
{
    protected static string $resource = RespondenPembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
