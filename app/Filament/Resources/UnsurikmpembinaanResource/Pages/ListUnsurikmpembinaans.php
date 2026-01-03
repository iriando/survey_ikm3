<?php

namespace App\Filament\Resources\UnsurikmpembinaanResource\Pages;

use App\Filament\Resources\UnsurikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnsurikmpembinaans extends ListRecords
{
    protected static string $resource = UnsurikmpembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat unsur baru'),
        ];
    }
}
