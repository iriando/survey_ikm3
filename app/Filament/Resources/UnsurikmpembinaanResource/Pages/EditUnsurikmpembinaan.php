<?php

namespace App\Filament\Resources\UnsurikmpembinaanResource\Pages;

use App\Filament\Resources\UnsurikmpembinaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnsurikmpembinaan extends EditRecord
{
    protected static string $resource = UnsurikmpembinaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
