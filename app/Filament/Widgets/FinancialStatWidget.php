<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class FinancialStatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Member Bulan Ini', 
                Member::whereBetween('created_at',[Carbon::now()->year.'-'.Carbon::now()->month.'-01',Date::now()])->count())
            ->color('success')
            ->extraAttributes([
                // 'class' => 'cursor-pointer',
                // 'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
            ])
            ->description('Total member baru yang daftar di bulan ini')
            ->descriptionIcon('heroicon-m-user'),

            Stat::make('Pendapatan Bulan Ini (IDR)', 
                number_format(Payment::where('status','accepted')->whereBetween('payment_date',[Carbon::now()->year.'-'.Carbon::now()->month.'-01',Date::now()])->sum('amount'),0,',','.'))
                ->color('success')
                ->extraAttributes([
                // 'class' => 'cursor-pointer',
                // 'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
                ])
                ->description('Total pembayaran yang diterima bulan ini')
                ->descriptionIcon('heroicon-m-credit-card'),

            Stat::make('Outstanding (IDR)', 
                number_format(Invoice::whereIn('status',['unpaid'])->sum('amount'),0,',','.'))
            ->color('success')
            ->extraAttributes([
                // 'class' => 'cursor-pointer',
                // 'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
            ])
            ->description('Total invoice yang belum dibayar')
            ->descriptionIcon('heroicon-m-bell-alert'),

            Stat::make('Pending Verifikasi (IDR)', 
                number_format(Payment::whereIn('status',['pending'])->sum('amount'),0,',','.'))
            ->color('success')
            ->extraAttributes([
                // 'class' => 'cursor-pointer',
                // 'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
            ])
            ->description('Total pembayaran yang belum diverifikasi')
            ->descriptionIcon('heroicon-m-banknotes')
        ];
    }
}
