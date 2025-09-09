<?php

namespace App\Filament\Portal\Resources\LeaveResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Portal\Resources\LeaveResource;
use App\Infolists\Components\ViewPaymentAttachment;
use Illuminate\Contracts\Support\Htmlable;

class ViewLeave extends ViewRecord
{
    protected static string $resource = LeaveResource::class;


    public function infolist(Infolist $infolist): Infolist
    {
        $infolist->schema([
            TextEntry::make('member.name')
                ->label('Nama Member'),
            TextEntry::make('start_date')
                ->label('Periode Cuti')
                ->date(),
            TextEntry::make('end_date')
                ->label('Sampai Dengan')
                ->date(),
            TextEntry::make('biaya')
                ->label('Biaya')
                ->money('idr', true),
            ViewPaymentAttachment::make('file_name')
                ->label('Bukti Pembayaran')->columnSpanFull(),
            TextEntry::make('status')
                ->label('Status')->formatStateUsing(function ($state) {
                            return match ($state) {
                                0 => 'Pending',
                                1 => 'Approved',
                                2 => 'Rejected',    
                                default => 'Unknown',
                            };
                        })->badge(),
            TextEntry::make('created_at')
                ->label('Diajukan Pada')
                ->dateTime(),
            TextEntry::make('approver.name')
                ->label('Divalidasi Oleh')
        ])->columns(4);

        return $infolist;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Lihat Cuti';
    }
}
