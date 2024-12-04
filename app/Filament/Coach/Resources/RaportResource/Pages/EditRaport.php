<?php

namespace App\Filament\Coach\Resources\RaportResource\Pages;

use App\Filament\Coach\Resources\RaportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRaport extends EditRecord
{
    protected static string $resource = RaportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
