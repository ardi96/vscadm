<?php

namespace App\Filament\Portal\Resources\PaymentResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Portal\Resources\PaymentResource;
use Filament\Notifications\Actions\Action as ActionsAction;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected static bool $canCreateAnother = false;

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
        

        $users = User::permission('approve payment')->get();

        foreach( $users as $user)
        {

            Notification::make()
                ->body('Payment receipt has been uploaded by the member')
                ->actions([
                    ActionsAction::make('view')->label('View')->url(PaymentResource::getUrl(name: 'view', parameters: ['record' => $this->record->id ], panel : 'admin'))
                ])
                ->sendToDatabase( $user );
              
        }
    }
}
