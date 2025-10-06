<?php

namespace App\Filament\Resources\UnsurikmtuResource\Pages;

use App\Filament\Resources\UnsurikmtuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnsurikmtu extends EditRecord
{
    protected static string $resource = UnsurikmtuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
