<?php

namespace App\Filament\Resources\GeneralInfoResource\Pages;

use App\Filament\Resources\GeneralInfoResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewGeneralInfo extends ViewRecord
{
    protected static string $resource = GeneralInfoResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('info')->markdown()
        ]);
    }
}
