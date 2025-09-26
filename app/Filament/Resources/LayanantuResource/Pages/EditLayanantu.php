<?php

namespace App\Filament\Resources\LayanantuResource\Pages;

use App\Filament\Resources\LayanantuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLayanantu extends EditRecord
{
    protected static string $resource = LayanantuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
