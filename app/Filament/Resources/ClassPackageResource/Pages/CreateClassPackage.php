<?php

namespace App\Filament\Resources\ClassPackageResource\Pages;

use App\Filament\Resources\ClassPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassPackage extends CreateRecord
{
    protected static string $resource = ClassPackageResource::class;

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
            return $resource::getUrl('view', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        }

        // if ($resource::hasPage('edit') && $resource::canEdit($this->getRecord())) {
        //     return $resource::getUrl('edit', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        // }

        return $resource::getUrl('index');
    }
}
