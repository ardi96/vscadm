<?php

namespace App\Filament\Coach\Resources\RaportResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Coach\Resources\RaportResource;

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
