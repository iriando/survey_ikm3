<?php

namespace App\Filament\Resources\NarasumberResource\Pages;

use App\Filament\Resources\NarasumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNarasumber extends EditRecord
{
    protected static string $resource = NarasumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
