<?php

namespace App\Filament\Resources\ReactivationRequestResource\Pages;

use App\Filament\Resources\ReactivationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReactivationRequest extends EditRecord
{
    protected static string $resource = ReactivationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
