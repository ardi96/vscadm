<?php

namespace App\Filament\Resources\ResignationResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use App\Services\ResignationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ResignationResource;

class ViewResignation extends ViewRecord
{
    protected static string $resource = ResignationResource::class;


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Resignation Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('member.name')->label('Nama Member')
                            ->formatStateUsing( fn ($record) => 'VSC' . str_pad($record->member->id, 4, '0', STR_PAD_LEFT) . ' - ' . $record->member->name ),
                        TextEntry::make('resignation_date')->label('Tanggal Efektif Pengunduran Diri')->date('d-M-Y'),
                        TextEntry::make('reason')->label('Alasan Pengunduran Diri'),
                        TextEntry::make('created_at')->label('Dibuat Pada')->dateTime('d-M-Y H:i'),
                        TextEntry::make('updated_at')->label('Diperbarui Pada')->dateTime('d-M-Y H:i'),
                        TextEntry::make('status')->label('Status')->formatStateUsing(function ($state) {
                            return match ($state) {
                                0 => 'Pending',
                                1 => 'Approved',
                                2 => 'Rejected',    
                                default => 'Unknown',
                            };
                        })->badge(),
                        TextEntry::make('approver.name')->label('Divalidasi Oleh')->placeholder('-'),
                    ]),
             ]);    
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve Resignation')
                ->icon('heroicon-o-check-circle')
                ->action(function() {

                    $member = $this->getRecord()->member;

                    if ( $member->balance == 0) {
                        ResignationService::approveResignation($this->getRecord(), auth()->user());

                        Notification::make()
                            ->title('Berhasil Approve Pengunduran Diri')
                            ->body('Pengunduran diri member telah disetujui.')
                            ->success()
                            ->send();
                    }
                    else
                    {
                        Notification::make()
                            ->title('Gagal Approve Pengunduran Diri')
                            ->body('Member masih ada outstanding balance.')
                            ->danger()
                            ->send();
                    }
                
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve resignation')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status', 'approver'])),
            
            Actions\Action::make('reject')
                ->label('Reject Resignation')
                ->color('danger')
                ->icon('heroicon-m-x-circle')
                ->action(function() {
                    $this->getRecord()->status = 2; // Set status to 'rejected'
                    $this->getRecord()->approver_id = auth()->user()->id; // Set approver to current user
                    $this->getRecord()->save();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->getRecord()->status == 0 && auth()->user()->can('approve resignation')) // Only show if status is 'pending'
                ->after(fn() => $this->refreshFormData(['status', 'approver'])),
        ];
    }
}
