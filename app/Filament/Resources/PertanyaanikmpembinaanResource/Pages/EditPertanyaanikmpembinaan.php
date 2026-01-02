<?php

namespace App\Filament\Resources\PertanyaanikmpembinaanResource\Pages;

use App\Filament\Resources\PertanyaanikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPertanyaanikmpembinaan extends EditRecord
{
    protected static string $resource = PertanyaanikmpembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
