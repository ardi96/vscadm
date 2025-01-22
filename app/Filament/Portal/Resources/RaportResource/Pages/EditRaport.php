<?php

namespace App\Filament\Portal\Resources\RaportResource\Pages;

use App\Filament\Portal\Resources\RaportResource;
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
