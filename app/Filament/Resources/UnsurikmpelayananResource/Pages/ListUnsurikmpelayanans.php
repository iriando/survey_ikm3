<?php

namespace App\Filament\Resources\UnsurikmpelayananResource\Pages;

use App\Filament\Resources\UnsurikmpelayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnsurikmpelayanans extends ListRecords
{
    protected static string $resource = UnsurikmpelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat unsur baru'),
        ];
    }

    public function getTitle(): string
    {
        return 'Unsur IKM Pelayanan';
    }
}
