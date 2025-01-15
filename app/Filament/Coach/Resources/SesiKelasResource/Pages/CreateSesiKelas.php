<?php

namespace App\Filament\Coach\Resources\SesiKelasResource\Pages;

use App\Filament\Coach\Resources\SesiKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSesiKelas extends CreateRecord
{
    protected static string $resource = SesiKelasResource::class;

    protected function afterCreate() : void
    {
        $record = $this->getRecord();

        $record->prepareListOfStudent();
    }

}
