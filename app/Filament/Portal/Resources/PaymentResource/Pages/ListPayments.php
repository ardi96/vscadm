<?php

namespace App\Filament\Portal\Resources\PaymentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\PaymentResource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    public function getTitle(): string | Htmlable
    {
        return "Riwayat Pembayaran";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Upload Bukti Pembayaran')
                ->visible(true)
                ->icon('heroicon-o-plus-circle')
        ];
    }
}
