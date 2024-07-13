<?php

namespace App\Filament\Resources\ClassLocationResource\Pages;

use App\Filament\Resources\ClassLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageClassLocations extends ManageRecords
{
    protected static string $resource = ClassLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
