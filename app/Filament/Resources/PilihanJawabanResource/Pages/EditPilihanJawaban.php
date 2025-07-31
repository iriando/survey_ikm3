<?php

namespace App\Filament\Resources\PilihanJawabanResource\Pages;

use App\Filament\Resources\PilihanJawabanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPilihanJawaban extends EditRecord
{
    protected static string $resource = PilihanJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
