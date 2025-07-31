<?php

namespace App\Filament\Resources\UnsurResource\Pages;

use App\Filament\Resources\UnsurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnsurs extends ListRecords
{
    protected static string $resource = UnsurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
