<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Tables\Actions\Action;
use Forms\Components\TextInput;
use App\Jobs\GenerateInvoiceJob;
use Filament\Infolists\Infolist;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\MemberResource;
use App\Jobs\SendInvoiceMail;
use App\Services\InvoiceService;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;

class MemberInvoices extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')->required()->label('Jumlah'),
                Forms\Components\TextInput::make('item_description')->required()->label('Keterangan'),
            ]);
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('No. Invoice')->alignCenter()->sortable(),
                TextColumn::make('invoice_date')->date('d-M-Y')->label('Tanggal Invoice'),
                TextColumn::make('item_description')->label('Paket'),
                TextColumn::make('amount')->money('IDR')->label('Jumlah'),
                TextColumn::make('status')->label('Status')
            ])
            ->poll('10s')
            ->headerActions([
                Tables\Actions\Action::make('Create New Invoice')
                    ->action(function() {

                       $invoice = InvoiceService::generate($this->getRecord());

                    })
                    ->requiresConfirmation()
            ])
            ->actions([
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
            ]);
    }
}
