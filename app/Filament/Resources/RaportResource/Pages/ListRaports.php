<?php

namespace App\Filament\Resources\RaportResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RaportResource;

class ListRaports extends ListRecords
{
    protected static string $resource = RaportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

}
