<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use DeepCopy\Filter\Filter;
use App\Jobs\SendInvoiceMail;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Filament\Resources\MemberResource\Pages\MemberInvoices;
use Filament\Forms\Components\TextInput;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')->required()->label('Jumlah'),
                TextInput::make('item_description')->required()->label('Keterangan'),
            ]);
    }

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
                
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Cancel')->action(function(Invoice $record) {
                        $record->cancel();})
                    ->requiresConfirmation()
                    ->label('Batalkan Invoice')
                    ->visible(fn(Invoice $record) => ( $record->status == 'unpaid' || $record->status == 'draft' ))
                    ->icon('heroicon-o-x-circle'),
                    
                    Tables\Actions\Action::make('send')->label('Kirim Invoice')
                    ->requiresConfirmation()
                    ->visible(fn($record) => ($record->status=='unpaid'))
                    ->action(function(Invoice $record) {
                        SendInvoiceMail::dispatch($record);})
                    ->icon('heroicon-o-envelope'),

                    Tables\Actions\Action::make('pay')->label('Telah dibayar')
                    ->requiresConfirmation()
                    ->visible(fn($record) => ($record->status=='unpaid'))
                    ->action(function(Invoice $record) {
                        $record->payNow(); })
                    ->icon('heroicon-o-check-circle'),
                    
                    Tables\Actions\EditAction::make()->label('Edit Invoice')
                    ->visible(fn($record) => ($record->status =='unpaid'))
                    ->icon('heroicon-o-pencil'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl( fn(Invoice $record): string => 
                MemberInvoices::getUrl(['record' => $record->member])
            )
            ->defaultSort('invoice_no','desc');
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
            // 'create' => Pages\CreateInvoice::route('/create'),
            // 'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
