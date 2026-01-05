<?php

namespace App\Filament\Resources\RespondenTuResource\Pages;

use App\Filament\Resources\RespondenTuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRespondenTu extends EditRecord
{
    protected static string $resource = RespondenTuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
