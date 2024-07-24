<?php

namespace App\Filament\Portal\Widgets;

use App\Filament\Portal\Resources\InvoiceResource;
use App\Filament\Portal\Resources\MemberResource;
use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class FamilyMemberStatistic extends BaseWidget
{
    private $latestPaidInvoiceAmount = 0;
    
    private $latestPaidInvoiceDate = null ; 

    protected function getStats(): array
    {
        $latestPaidInvoice = Invoice::where('parent_id',Auth()->user()->id)->where('status','paid')->latest()->first();

        if ( $latestPaidInvoice != null )
        {
            $this->latestPaidInvoiceAmount = $latestPaidInvoice->amount;
            $this->latestPaidInvoiceDate = $latestPaidInvoice->payment_date;
        } 

        return [
            Stat::make('Registrasi', Member::where('parent_id', Auth()->user()->id)->count())
            ->color('success')
            ->extraAttributes([
                'class' => 'cursor-pointer',
                // 'wire:click' => "window.location.href='". MemberResource::getUrl('create'). "'",
                'onclick' => "window.location.href='". MemberResource::getUrl('create'). "'",
            ])
            ->description('Terdaftar dengan akun Anda. Klik untuk registrasi baru.')
            ->descriptionIcon('heroicon-m-user'),

            Stat::make('Outstanding', 'IDR '. number_format(Invoice::where('parent_id',Auth()->user()->id)->where('status','unpaid')->sum('amount'),0,',','.'))
            ->color('success')
            ->extraAttributes([
                'class' => 'cursor-pointer',
                'onclick' => "window.location.href='". InvoiceResource::getUrl('index'). "'",
            ])
            ->description('Total Outstanding Fee')
            ->descriptionIcon('heroicon-m-banknotes'),

            Stat::make('Pembayaran Terakhir', 'IDR '. number_format($this->latestPaidInvoiceAmount,0,',','.'))
            ->color('success')
            ->description(date_format(date_create($this->latestPaidInvoiceDate),'d-M-Y'))
            ->descriptionIcon('heroicon-m-check-badge')
        ];
    }
}
