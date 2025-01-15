<?php

namespace App\Filament\Coach\Resources\SesiKelasResource\Pages;

use App\Filament\Coach\Resources\SesiKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSesiKelas extends EditRecord
{
    protected static string $resource = SesiKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
