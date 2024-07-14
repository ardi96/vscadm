<?php

namespace App\Filament\Resources\ParentResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Jobs\SendInvoiceMail;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ParentResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParentInvoices extends ManageRelatedRecords
{
    protected static string $resource = ParentResource::class;

    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Invoices';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Invoices';
    }

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
            ->recordTitleAttribute('invoice_no')
            ->columns([
                Tables\Columns\TextColumn::make('member.name')->label('Nama Anggota'),
                Tables\Columns\TextColumn::make('invoice_no')->label('No. Invoice')->sortable(),
                Tables\Columns\TextColumn::make('invoice_date')->label('Tgl. Invoice')->date('d-M-Y'),
                Tables\Columns\TextColumn::make('item_description')->label('Nama Paket'),
                Tables\Columns\TextColumn::make('amount')->label('Jumlah')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('invoice_no','desc');;
    }
}
