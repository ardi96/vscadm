<?php

namespace App\Filament\Resources\BulkInvoiceResource\Pages;

use App\Filament\Resources\BulkInvoiceResource;
use App\Services\InvoiceService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBulkInvoice extends EditRecord
{
    protected static string $resource = BulkInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn($record) => $record->status == 'draft')
        ];
    }
}
