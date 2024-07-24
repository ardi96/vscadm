<?php

namespace App\Filament\Portal\Resources\InvoiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\InvoiceResource;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    public function getTitle(): string | Htmlable
    {
        return "Daftar Invoice";
    }

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
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','unpaid')),
            'pending' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending')),
            'paid' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','paid')),
            'cancel' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','void')),
        ];
    }
}
