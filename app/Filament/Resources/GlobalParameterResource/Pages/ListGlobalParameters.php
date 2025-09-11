<?php

namespace App\Filament\Resources\GlobalParameterResource\Pages;

use App\Filament\Resources\GlobalParameterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlobalParameters extends ListRecords
{
    protected static string $resource = GlobalParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Parameter'),
        ];
    }
}
