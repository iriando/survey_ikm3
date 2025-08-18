<?php

namespace App\Filament\Resources\PilihanJawabanikmpelayananResource\Pages;

use App\Filament\Resources\PilihanJawabanikmpelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPilihanJawabanikmpelayanan extends EditRecord
{
    protected static string $resource = PilihanJawabanikmpelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
