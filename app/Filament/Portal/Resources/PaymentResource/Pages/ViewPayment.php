<?php

namespace App\Filament\Portal\Resources\PaymentResource\Pages;

use App\Filament\Portal\Resources\PaymentResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('payment_date')->label('Tanggal Pembayaran')->formatStateUsing(fn ($state) => date_format(date_create($state), 'd-M-Y')),
                TextEntry::make('bank')->label('Nama Bank'),
                TextEntry::make('amount')->label('Jumlah Pembayaran')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' Rupiah'),
                TextEntry::make('notes')->label('Keterangan'),
                TextEntry::make('status')->label('Status'),
            ]);
    }
}
