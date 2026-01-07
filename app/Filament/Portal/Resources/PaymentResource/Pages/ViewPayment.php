<?php

namespace App\Filament\Portal\Resources\PaymentResource\Pages;

use Filament\Actions;
use Nette\Utils\Html;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Portal\Resources\PaymentResource;
use Filament\Infolists\Components\ViewEntry;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;




    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('payment_status')->label('')
                    ->visible(fn ($record) => $record->status == 'pending')
                    ->columnSpanFull()
                    ->state('<ul><li><b>Status : </b> Menunggu verifikasi admin</li><li><b>Estimasi : </b> Maks 2x24 jam</li></ul>')->html(),
                TextEntry::make('status')
                    ->badge()
                    ->label('Status')->formatStateUsing(fn ($state) => ucwords($state))->visible( fn($state) => $state != 'pending'),
                TextEntry::make('payment_date')->label('Tanggal Pembayaran')->formatStateUsing(fn ($state) => date_format(date_create($state), 'd-M-Y')),
                TextEntry::make('bank')->label('Nama Bank'),
                TextEntry::make('amount')->label('Jumlah Pembayaran')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' Rupiah'),
                TextEntry::make('notes')->label('Keterangan'),
                TableRepeatableEntry::make('invoices')
                    ->label('Untuk Pembayaran Invoice')
                    ->schema([
                        TextEntry::make('invoice_no')->label('No. Invoice'),
                        TextEntry::make('description')->label('Keterangan'),
                        TextEntry::make('amount')->label('Jumlah')->money('IDR'),
                    ])->columnSpanFull(),
            ])->columns(3);
    }
}
