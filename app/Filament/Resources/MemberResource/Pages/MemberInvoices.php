<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Tables;
use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Tables\Actions\Action;
use Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\MemberResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;

class MemberInvoices extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->label('Buat Invoice Baru'),
    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_no')
                    ->required()
                    ->maxLength(40),
            ]);
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no'),
                TextColumn::make('invoice_date')->date('d-M-Y'),
                TextColumn::make('amount')->money('IDR')
            ])
            ->headerActions([
                Tables\Actions\Action::make('Create New Invoice')
                    ->action(fn() => '')
            ]);
    }
}
