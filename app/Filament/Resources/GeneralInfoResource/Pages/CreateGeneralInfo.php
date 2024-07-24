<?php

namespace App\Filament\Resources\GeneralInfoResource\Pages;

use App\Filament\Resources\GeneralInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralInfo extends CreateRecord
{
    protected static string $resource = GeneralInfoResource::class;

     /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
            return $resource::getUrl('view', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        }
        
        return $resource::getUrl('index');
    }

}
