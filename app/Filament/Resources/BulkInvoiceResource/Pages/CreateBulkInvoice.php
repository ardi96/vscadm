<?php

namespace App\Filament\Resources\BulkInvoiceResource\Pages;

use App\Filament\Resources\BulkInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBulkInvoice extends CreateRecord
{
    protected static string $resource = BulkInvoiceResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'draft';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        // if ($resource::hasPage('view') && $resource::canView($this->getRecord())) {
        //     return $resource::getUrl('view', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        // }

        // if ($resource::hasPage('edit') && $resource::canEdit($this->getRecord())) {
        //     return $resource::getUrl('edit', ['record' => $this->getRecord(), ...$this->getRedirectUrlParameters()]);
        // }

        return $resource::getUrl('index');
    }


}
