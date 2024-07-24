<?php

namespace App\Filament\Portal\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\InvoiceResource;
use Illuminate\Support\Facades\Auth;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    public function getTitle(): string | Htmlable
    {
        return "Daftar Tagihan";
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
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','unpaid'))
                        ->badge(Invoice::where('status','unpaid')->where('parent_id',Auth::user()->id)->count()),
            'pending' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending'))
                        ->badge(Invoice::where('status','pending')->where('parent_id',Auth::user()->id)->count()),
            'paid' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','paid'))
                        ->badge(Invoice::where('status','paid')->where('parent_id',Auth::user()->id)->count()),
            'cancel' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','void'))
                        ->badge(Invoice::where('status','void')->where('parent_id',Auth::user()->id)->count()),
            'all' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->whereNot('status','ngantuk'))
                        ->badge(Invoice::where('parent_id',Auth::user()->id)->count()),
        ];
    }
}
