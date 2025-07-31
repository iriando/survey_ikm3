<?php

namespace App\Filament\Resources\NilaiPersepsiIkmResource\Pages;

use App\Filament\Resources\NilaiPersepsiIkmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilaiPersepsiIkms extends ListRecords
{
    protected static string $resource = NilaiPersepsiIkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
