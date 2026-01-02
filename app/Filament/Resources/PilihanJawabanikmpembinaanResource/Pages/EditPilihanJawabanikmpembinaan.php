<?php

namespace App\Filament\Resources\PilihanJawabanikmpembinaanResource\Pages;

use App\Filament\Resources\PilihanJawabanikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPilihanJawabanikmpembinaan extends EditRecord
{
    protected static string $resource = PilihanJawabanikmpembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
