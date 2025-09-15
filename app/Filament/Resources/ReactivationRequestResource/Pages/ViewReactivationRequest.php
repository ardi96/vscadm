<?php

namespace App\Filament\Resources\ReactivationRequestResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use App\Services\ReactivationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Infolists\Components\ViewPaymentAttachment;
use App\Filament\Resources\ReactivationRequestResource;

class ViewReactivationRequest extends ViewRecord
{
    protected static string $resource = ReactivationRequestResource::class;


    

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('member.id')->label('Member Name')->formatStateUsing(fn($record) => 'VSC' . str_pad($record->member->id, 4, '0', STR_PAD_LEFT). ' - ' . $record->member->name ),
            TextEntry::make('amount')->label('Total Pembayaran')->money('IDR', true),
            TextEntry::make('created_at')->label('Dibuat Pada')->dateTime(),
            TextEntry::make('status')->formatStateUsing(function ($state) {
                    return match ($state) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                        default => 'Unknown',
                    };
                })->colors([
                    'warning' => 0,
                    'success' => 1,
                    'danger' => 2,
                ])->badge(),
            Section::make('Berkas')
                    ->columns(1)
                    ->schema([
                        ViewPaymentAttachment::make('file_name')
                            ->label('Bukti Bayar')
                    ]),
        ])->columns(4)
        ;
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->action(function() {

                    $member = $this->getRecord()->member;
                        
                    ReactivationService::approveReactivation($this->getRecord(), auth()->user());

                    Notification::make()
                        ->title('Reaktivasi Berhasi')
                        ->body('Reaktivasi berhasil dan status member menjadi aktiv.')
                        ->success()
                        ->send();
                
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve reactivation request')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status'])),
            
            Actions\Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->action(function() {
                    $this->getRecord()->status = 2; // Set status to 'rejected'
                    $this->getRecord()->approver_id = auth()->user()->id; // Set approver to current user
                    $this->getRecord()->save();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve reactivation request')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status', 'approver'])),
        ];
    }
}
