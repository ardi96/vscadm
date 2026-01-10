<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Actions;
use App\Models\Payment;
use Filament\Resources\Components\Tab;
use App\Filament\Exports\PaymentExporter;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaymentResource;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Export Data')
                ->exporter(PaymentExporter::class)
                ->visible(auth()->user()->can('export payment'))
            // Actions\CreateAction::make(),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'pending';
    }

    public function getTabs(): array
    {
        return [
            'accepted' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','accepted'))
                        ->badge(Payment::where('status','accepted')->count()),
            'pending' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending')->where('is_online', false))
                        ->badge(Payment::where('status','pending')->where('is_online', false)->count()),
            'rejected' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','rejected')->where('is_online', false))
                        ->badge(Payment::where('status','rejected')->where('is_online', false)->count()),
            'all' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('is_online', false)->orWhere(function ($query) {
                            $query->where('is_online', true)
                                  ->where('status', 'accepted');
                        }))
                        ->badge(Payment::where('is_online', false)->orWhere(function ($query) {
                            $query->where('is_online', true)
                                  ->where('status', 'accepted');
                        })->count()),
        ];
    }
}