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
                ->createAnother(false)
                ->beforeFormFilled(function (Actions\CreateAction $action) {
                    
                    $user = Auth::user();
                    
                    if ( $user->invoices()->where('status', 'unpaid')->count() == 0 ) {
                        
                        Notification::make()
                            ->title('Peringatan')
                            ->body('Tidak ada invoice yang belum dibayar. Anda tidak dapat mengunggah bukti pembayaran.')
                            ->danger()
                            ->persistent()
                            ->send();

                        $action->cancel();
                    } 

                })
                ->icon('heroicon-o-plus-circle')
        ];
    }
}
