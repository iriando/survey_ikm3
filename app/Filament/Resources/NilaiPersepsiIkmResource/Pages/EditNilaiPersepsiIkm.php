<?php

namespace App\Filament\Resources\NilaiPersepsiIkmResource\Pages;

use App\Filament\Resources\NilaiPersepsiIkmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNilaiPersepsiIkm extends EditRecord
{
    protected static string $resource = NilaiPersepsiIkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
