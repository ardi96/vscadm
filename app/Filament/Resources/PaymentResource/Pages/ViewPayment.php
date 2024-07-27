<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Member;
use App\Models\Payment;
use Filament\Infolists\Infolist;
use App\Notifications\MemberAccepted;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PaymentResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Infolists\Components\ViewPaymentAttachment;
use App\Jobs\SendPaymentRejectionEmail;
use Filament\Forms\Components\TextInput;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reject')->label('Reject Pembayaran')
                ->color('danger')
                ->visible(fn() => $this->getRecord()->status == 'pending')
                ->action(function($data) {
                    $this->getRecord()->status = 'rejected';
                    $this->getRecord()->rejection_note = $data['rejection_note'];
                    $this->getRecord()->save();

                    SendPaymentRejectionEmail::dispatch( $this->getRecord() );

                    foreach( $this->getRecord()->invoices as $invoice)
                    {
                        $invoice->cancelPayment();
                    }

                })
                ->icon('heroicon-m-x-circle')
                ->requiresConfirmation()
                ->form([
                    TextInput::make('rejection_note')->label('Alasan')->required()
                ])
                ->after(fn() => $this->refreshFormData(['status'])),

            Actions\Action::make('accept')->label('Terima Pembayaran')
                ->visible(fn() => $this->getRecord()->status == 'pending')
                ->action(function() {
                    $this->getRecord()->status = 'accepted';
                    $this->getRecord()->save();

                    $payment = Payment::find($this->getRecord()->id);
                    
                    foreach($payment->invoices as $invoice)
                    {
                        $invoice->payNow();

                        if ( $invoice->type == 'registration')
                        {
                            $member = Member::find($invoice->member_id);
                            
                            $member->status = 'active';
                            
                            $member->save();
                            
                            $user = User::find($member->parent_id);
                            
                            $user->notify(new MemberAccepted( $member ));
                        }
                    }

                })
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation()
                ->after(fn() => $this->refreshFormData(['status'])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('payment_date')->label('Tanggal Pembayaran')->date('d-M-Y'),
            TextEntry::make('amount')->label('Jumlah Pembayaran')->money('IDR'),
            TextEntry::make('notes')->label('Keterangan'),
            TextEntry::make('bank')->label('Dari Bank'),
            TextEntry::make('created_at')->label('Tanggal Upload')->dateTime('d-M-Y H:i:s'),
            TextEntry::make('status')->label('Status Konfirmasi')
                ->badge()
                ->color(fn(string $state):string => match($state) {
                    'accepted' => 'primary',
                    'pending' => 'secondary',
                    'rejected' => 'danger',
                })
                ->icon(fn(string $state):string => match($state) {
                    'accepted' => 'heroicon-m-check-circle',
                    'pending' => 'heroicon-m-question-mark-circle',
                    'rejected' => 'heroicon-m-x-circle',
                }),
            RepeatableEntry::make('invoices')
                ->label('')
                ->schema([
                    TextEntry::make('invoice_no')->label('No. Invoice'),                
                    TextEntry::make('invoice_date')->date('d-M-Y')->label('Tgl. Invoice'),                
                    TextEntry::make('amount')->label('Jumlah')->money('IDR'),                
                    TextEntry::make('description')->label('Judul Invoice'),                
                    TextEntry::make('item_description')->label('Nama Paket'),                
                    TextEntry::make('member.name')->label('Nama Member'),                
            ])->columns(6)->columnSpanFull(),
            ViewPaymentAttachment::make('file_name')->label('Bukti Pembayaran'),
        ])->columns(6);
    }
}