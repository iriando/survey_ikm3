<?php

namespace App\Filament\Resources\LayanantuResource\Pages;

use App\Filament\Resources\LayanantuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLayanantus extends ListRecords
{
    protected static string $resource = LayanantuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat layanan baru'),
        ];
    }

    public function getTitle(): string
    {
        return 'Layanan Bagian Tata Usaha';
    }
}
