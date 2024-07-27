<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\InvoiceResource\Pages;
use App\Filament\Portal\Resources\InvoiceResource\RelationManagers;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Tagihan';

    protected static ?int $navigationSort = 20;

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('No. Invoice')->sortable()->searchable(),
                TextColumn::make('member.name')->label('Atas Nama')->searchable()->sortable(),
                TextColumn::make('invoice_date')->label('Tgl. Invoice')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('description')->label('Keterangan')->searchable()->sortable(),
                TextColumn::make('item_description')->label('Nama Paket')->searchable()->sortable(),
                TextColumn::make('amount')->label('Jumlah')->money('IDR')->searchable()->sortable(),
                TextColumn::make('status')->label('Status')->searchable()->sortable()
                    ->badge()
                    ->color(fn(string $state):string => match($state) {
                        'paid' => 'primary',
                        'pending' => 'secondary',
                        'unpaid' => 'info',
                        'void' => 'danger',
                    })
                    ->icon(fn(string $state):string => match($state) {
                        'paid' => 'heroicon-m-check-circle',
                        'pending' => 'heroicon-m-question-mark-circle', 
                        'unpaid' => 'heroicon-m-no-symbol',
                        'void' => 'heroicon-m-x-circle',
                    }),
                TextColumn::make('payment_date')->label('Tgl. Pembayaran')->date('d-M-Y')->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'unpaid' => 'Unpaid',
                    'pending' => 'Pending Verification',
                    'paid' => 'Paid',
                    'void' => 'Void',
                ])
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Bayar')->icon('heroicon-m-banknotes')->label('Bayar')
                        ->url(PaymentResource\Pages\CreatePayment::getUrl())
                        ->visible(fn($record) => $record->status == 'unpaid')
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('invoice_no','desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('parent_id', Auth::user()->id));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
