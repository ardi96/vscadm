<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'unpaid';
    }

    public function getTabs(): array
    {
        return [
            'unpaid' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','unpaid'))
                        ->badge(Invoice::where('status','unpaid')->count()),
            'pending' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending'))
                        ->badge(Invoice::where('status','pending')->count()),
            'paid' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','paid'))
                        ->badge(Invoice::where('status','paid')->count()),
            'cancel' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','void'))
                        ->badge(Invoice::where('status','void')->count()),
            'all' => Tab::make()
                        ->badge(Invoice::count()),
        ];
    }
}
