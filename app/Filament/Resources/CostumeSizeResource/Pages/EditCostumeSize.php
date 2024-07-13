<?php

namespace App\Filament\Resources\CostumeSizeResource\Pages;

use App\Filament\Resources\CostumeSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCostumeSize extends EditRecord
{
    protected static string $resource = CostumeSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
