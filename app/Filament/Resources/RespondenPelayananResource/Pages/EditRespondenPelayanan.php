<?php

namespace App\Filament\Resources\RespondenPelayananResource\Pages;

use App\Filament\Resources\RespondenPelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRespondenPelayanan extends EditRecord
{
    protected static string $resource = RespondenPelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
