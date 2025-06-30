<?php

namespace App\Filament\Portal\Resources\MemberResource\Pages;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Actions\Action;
use App\Models\PaymentInvoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\PaymentResource;
use App\Filament\Portal\Resources\MemberResource;
use Filament\Notifications\Actions\Action as ActionsAction;
use Illuminate\Support\Facades\URL;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['parent_id'] = Auth()->user()->id;

        // $data['payment_amount'] = $data['payment_amount'] * 1000;

        return $data; 
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }


    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            // ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Kirim Data Registrasi')
            // ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public function getTitle(): string | Htmlable
    {
        return 'Registrasi Baru';
    }
    
    protected function handleRecordCreation(array $data): Model
    {

        $payment_date = $data['payment_date'];
        $bank = $data['bank'];
        $notes = $data['notes'];
        
        unset($data['bank']);
        unset($data['payment_date']);
        unset($data['notes']);

        //insert the member information
        $record =  static::getModel()::create($data);

        
        // Create a new payment
        $payment = new Payment();
        $payment->amount = $data['payment_amount'];
        $payment->payment_date = $payment_date;
        $payment->bank = $bank;
        $payment->notes  = $notes;
        $payment->user_id = $data['parent_id'];
        $payment->file_name = $record->payment_file_name;

        // link the member_id with this payment
        $payment->member_id = $record->id;

        // Save the payment model to insert the data
        $payment->save();

        // generate invoice for registration fee
        $invoice = InvoiceService::generateRegistrationInvoice($record , $payment);

        PaymentInvoice::create([
            'invoice_id' => $invoice->id,
            'payment_id' => $payment->id 
        ]);


        // here we want to send notification to the users whose permission to "approve payment"
        $users = User::permission('approve payment')->get();

        foreach( $users as $user)
        {

            Notification::make()
                ->body('Payment receipt has been uploaded by the member')
                ->actions([
                    ActionsAction::make('view')->label('View')->url(PaymentResource::getUrl(name: 'view', parameters: ['record' => $payment->id ], panel : 'admin'))
                ])
                ->sendToDatabase( $user );
              
        }
        
        return $record;
    }
}
