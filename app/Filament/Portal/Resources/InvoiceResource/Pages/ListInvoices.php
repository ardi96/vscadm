<?php

namespace App\Filament\Portal\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Portal\Resources\InvoiceResource;
use App\Filament\Portal\Resources\PaymentResource;

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
            Actions\Action::make('upload_payment_proof')->label('Upload Bukti Pembayaran')
                ->visible(true)
                ->icon('heroicon-o-plus-circle')
                ->url(PaymentResource::getUrl('create'))
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
                        ->badge(Invoice::where('status','unpaid')->where('parent_id',Auth::user()->id)
                        ->whereIn('member_id', Member::all()->pluck('id'))->count()),
            'pending' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending'))
                        ->badge(Invoice::where('status','pending')->where('parent_id',Auth::user()->id)
                        ->whereIn('member_id', Member::all()->pluck('id'))->count()),
            'paid' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','paid'))
                        ->badge(Invoice::where('status','paid')->where('parent_id',Auth::user()->id)
                        ->whereIn('member_id', Member::all()->pluck('id'))->count()),
            'cancel' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->where('status','void'))
                        ->badge(Invoice::where('status','void')->where('parent_id',Auth::user()->id)
                        ->whereIn('member_id', Member::all()->pluck('id'))->count()),
            'all' => Tab::make()
                        ->modifyQueryUsing(fn(Builder $query) => $query->whereNot('status','ngantuk'))
                        ->badge(Invoice::where('parent_id',Auth::user()->id)
                        ->whereIn('member_id', Member::all()->pluck('id'))->count()),
        ];
    }
}
