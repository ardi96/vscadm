<?php

namespace App\Filament\Resources\GeneralInfoResource\Pages;

use App\Filament\Resources\GeneralInfoResource;
use App\Models\GeneralInfo;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralInfos extends ListRecords
{
    protected static string $resource = GeneralInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn() => GeneralInfo::count() == 0),
        ];
    }
}
