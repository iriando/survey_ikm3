<?php

namespace App\Filament\Resources\UnsurikmtuResource\Pages;

use App\Filament\Resources\UnsurikmtuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnsurikmtus extends ListRecords
{
    protected static string $resource = UnsurikmtuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat unsur baru'),
        ];
    }

    public function getTitle(): string
    {
        return 'Unsur IKM Bagian Tata Usaha';
    }
}
