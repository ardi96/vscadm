<?php

namespace App\Filament\Coach\Resources\SesiKelasResource\Pages;

use App\Filament\Coach\Resources\SesiKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSesiKelas extends ListRecords
{
    protected static string $resource = SesiKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($record) {
                $record->prepareListOfStudent();
            }),
        ];
    }
}
