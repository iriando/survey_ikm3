<?php

namespace App\Filament\Resources\UnsurikmpelayananResource\Pages;

use App\Filament\Resources\UnsurikmpelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnsurikmpelayanan extends EditRecord
{
    protected static string $resource = UnsurikmpelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
