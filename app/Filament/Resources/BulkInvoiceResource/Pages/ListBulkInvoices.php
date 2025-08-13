<?php

namespace App\Filament\Resources\BulkInvoiceResource\Pages;

use App\Filament\Resources\BulkInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBulkInvoices extends ListRecords
{
    protected static string $resource = BulkInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Bulk Invoice'),
        ];
    }
}
