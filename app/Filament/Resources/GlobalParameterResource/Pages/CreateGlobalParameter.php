<?php

namespace App\Filament\Resources\GlobalParameterResource\Pages;

use App\Filament\Resources\GlobalParameterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGlobalParameter extends CreateRecord
{
    protected static string $resource = GlobalParameterResource::class;




    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
