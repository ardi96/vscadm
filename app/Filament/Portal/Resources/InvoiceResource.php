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
                TextColumn::make('invoice_no')->label('No. Invoice')->sortable(),
                TextColumn::make('member.name')->label('Atas Nama')->searchable(),
                TextColumn::make('invoice_date')->label('Tgl. Invoice')->date('d-M-Y'),
                TextColumn::make('item_description')->label('Nama Paket'),
                TextColumn::make('amount')->label('Jumlah')->money('IDR'),
                TextColumn::make('status')->label('Status'),
                TextColumn::make('payment_date')->label('Tgl. Pembayaran')->date('d-M-Y'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'unpaid' => 'Unpaid',
                    'paid' => 'Paid',
                    'void' => 'Void',
                ])
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
