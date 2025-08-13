<?php

namespace App\Filament\Resources\BulkInvoiceResource\Pages;

use Filament\Actions;
use App\Services\InvoiceService;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\BulkInvoiceResource;

class ViewBulkInvoice extends ViewRecord
{
    protected static string $resource = BulkInvoiceResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn($record) => $record->status == 'draft'),
                
            Actions\Action::make('approve')->label('Approve')
                ->requiresConfirmation()
                ->action(function($record)
                    {

                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                        ]);

                        foreach($record->bulk_invoice_members as $bulk_invoice_member) {
                            $invoice = InvoiceService::createInvoice(
                                $bulk_invoice_member->member,
                                $record->total_amount,
                                $record->invoice_date,
                                $record->invoice_title,
                                $record->invoice_item_description
                            );

                            $bulk_invoice_member->update([
                                'invoice_id' => $invoice->id,
                            ]);
                        }

                        return redirect( $this->getResource()::getUrl('view', ['record' => $record]) );

                    }
                )->visible(fn($record) => $record->status == 'draft' && $record->bulk_invoice_members->count() > 0)
        ];
    }
}
