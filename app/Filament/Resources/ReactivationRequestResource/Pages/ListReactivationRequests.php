<?php

namespace App\Filament\Resources\ReactivationRequestResource\Pages;

use App\Filament\Resources\ReactivationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReactivationRequests extends ListRecords
{
    protected static string $resource = ReactivationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
