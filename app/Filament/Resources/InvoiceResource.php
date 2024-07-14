<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Filament\Resources\MemberResource\Pages\MemberInvoices;
use App\Models\Invoice;
use DeepCopy\Filter\Filter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('No. Invoice'),
                TextColumn::make('member.name')->label('Atas Nama'),
                TextColumn::make('invoice_date')->label('Tgl. Invoice')->date('d-M-Y'),
                TextColumn::make('item_description')->label('Nama Paket'),
                TextColumn::make('amount')->label('Jumlah')->money('IDR'),
                TextColumn::make('status')->label('Status'),
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
                        $record->cancel();
                    })
                    ->requiresConfirmation()
                    ->label('Batalkan Invoice')
                    ->visible(fn(Invoice $record) => ( $record->status == 'unpaid' )),
                    
                    Tables\Actions\Action::make('pay')->label('Telah dibayar')
                    ->requiresConfirmation()
                    ->visible(fn($record) => ($record->status=='unpaid'))
                    ->action(function(Invoice $record) {
                        $record->payNow(); 
                    })
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl( fn(Invoice $record): string => 
                MemberInvoices::getUrl(['record' => $record->member])
            );
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
