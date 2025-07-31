<?php

namespace App\Filament\Resources\PertanyaanikmpelayananResource\Pages;

use App\Filament\Resources\PertanyaanikmpelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPertanyaanikmpelayanan extends EditRecord
{
    protected static string $resource = PertanyaanikmpelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
