<?php

namespace App\Filament\Resources\ClassSessionResource\Pages;

use App\Filament\Resources\ClassSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageClassSessions extends ManageRecords
{
    protected static string $resource = ClassSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
