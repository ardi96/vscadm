<?php

namespace App\Filament\Portal\Resources\PaymentResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\PaymentResource;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.

        $invoices = $this->record->invoices; 

        foreach( $invoices as $invoice)
        {
            $invoice->status = 'pending';
            $invoice->save();
        }
        
    }
}
