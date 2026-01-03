<?php

namespace App\Filament\Resources\RespondenPembinaanResource\Pages;

use App\Filament\Resources\RespondenPembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRespondenPembinaan extends EditRecord
{
    protected static string $resource = RespondenPembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
