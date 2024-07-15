<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Actions;
use App\Models\Payment;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PaymentResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Infolists\Components\ViewPaymentAttachment;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reject')->label('Reject Pembayaran')
                ->color('danger')
                ->visible(fn() => $this->getRecord()->status == 'pending')
                ->action(function() {
                    $this->getRecord()->status = 'rejected';
                    $this->getRecord()->save();
                })
                ->icon('heroicon-m-x-circle')
                ->requiresConfirmation()
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
            TextEntry::make('status')->label('Status Pembayaran'),
            RepeatableEntry::make('invoices')
                ->label('')
                ->schema([
                    TextEntry::make('invoice_no')->label('No. Invoice'),                
                    TextEntry::make('invoice_date')->date('d-M-Y')->label('Tgl. Invoice'),                
                    TextEntry::make('amount')->label('Jumlah')->money('IDR'),                
                    TextEntry::make('item_description')->label('Deskripsi/Nama Paket'),                
            ])->inlineLabel()->columns(4)->columnSpanFull(),
            ViewPaymentAttachment::make('file_name')->label('Bukti Pembayaran'),
        ]);
    }
}