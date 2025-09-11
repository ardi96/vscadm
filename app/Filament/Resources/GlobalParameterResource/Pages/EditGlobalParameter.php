<?php

namespace App\Filament\Resources\GlobalParameterResource\Pages;

use App\Filament\Resources\GlobalParameterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlobalParameter extends EditRecord
{
    protected static string $resource = GlobalParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
