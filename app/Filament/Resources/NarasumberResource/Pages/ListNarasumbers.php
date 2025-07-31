<?php

namespace App\Filament\Resources\NarasumberResource\Pages;

use App\Filament\Resources\NarasumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNarasumbers extends ListRecords
{
    protected static string $resource = NarasumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
