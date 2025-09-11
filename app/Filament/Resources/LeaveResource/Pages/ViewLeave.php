<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use Filament\Actions;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Illuminate\View\FileViewFinder;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\LeaveResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Infolists\Components\ViewPaymentAttachment;
use App\Services\LeaveService;
use Filament\Notifications\Notification;

class ViewLeave extends ViewRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve Cuti')
                ->icon('heroicon-o-check-circle')
                ->action(function() {

                    $from_year = Carbon::parse($this->getRecord()->start_date)->year;
                    $from_month = Carbon::parse($this->getRecord()->start_date)->month;

                    $member = $this->getRecord()->member;

                    $invoice = \App\Models\Invoice::where('invoice_period_year', $from_year)
                        ->where('invoice_period_month', $from_month)
                        ->where('member_id', $this->getRecord()->member_id)
                        ->whereNot('status', 'void')
                        ->first();
                    
                    if (!$invoice && $member->balance == 0) {
                        LeaveService::approveLeave($this->getRecord(), auth()->user()->id);
                    }
                    else
                    {
                        Notification::make()
                            ->title('Gagal Approve Cuti')
                            ->body('Terdapat invoice aktif pada periode cuti yang diajukan, atau member masih ada outstanding balance.')
                            ->danger()
                            ->send();
                    }
                
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve leave')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status', 'approver'])),
            Actions\Action::make('reject')
                ->label('Reject Cuti')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->action(function() {
                    $this->getRecord()->status = 2; // Set status to 'rejected'
                    $this->getRecord()->approved_by = auth()->user()->id; // Set approver to current user
                    $this->getRecord()->save();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve leave')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status', 'approver'])),
        ];
    }


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Cuti')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('member.name')->label('Nama Member'),
                        TextEntry::make('start_date')->label('Period Cuti')->date('M-Y'),
                        TextEntry::make('end_date')->label('Sampai Dengan')->date('M-Y'),
                        TextEntry::make('biaya')->label('Biaya')->money('IDR', true),
                        TextEntry::make('user.name')->label('Dibuat Oleh'),
                        TextEntry::make('created_at')->label('Dibuat Pada')->dateTime(),
                        TextEntry::make('updated_at')->label('Diubah Pada')->dateTime(),
                        TextEntry::make('status')->label('Status')->formatStateUsing(function ($state) {
                            return match ($state) {
                                0 => 'Pending',
                                1 => 'Approved',
                                2 => 'Rejected',    
                                default => 'Unknown',
                            };
                        })->badge(),
                        TextEntry::make('approver.name')->label('Divalidasi Oleh'),
                    ]),
                Section::make('Berkas Cuti')
                    ->columns(1)
                    ->schema([
                        ViewPaymentAttachment::make('file_name')
                            ->label('Bukti Bayar')
                    ]),
            ]);
    }

}
