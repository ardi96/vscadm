<?php

namespace App\Filament\Coach\Resources\AbsensiResource\Pages;

use App\Filament\Coach\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            // Actions\Action::make('submit')->label('Submit')->color('primary')->action(fn() => dd($this)),
        ];
    }

    
}
