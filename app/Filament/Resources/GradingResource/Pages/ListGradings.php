<?php

namespace App\Filament\Resources\GradingResource\Pages;

use App\Filament\Resources\GradingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGradings extends ListRecords
{
    protected static string $resource = GradingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Upload Raport'),
        ];
    }
}
