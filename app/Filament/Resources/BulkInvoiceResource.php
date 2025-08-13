<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BulkInvoice;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BulkInvoiceResource\Pages;
use App\Filament\Resources\BulkInvoiceResource\RelationManagers;

class BulkInvoiceResource extends Resource
{
    protected static ?string $model = BulkInvoice::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Finance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_number')->unique('bulk_invoices','invoice_number',null,true,null)->maxLength(10)->required()
                    ->label('Nomor Bulk Invoice'),
                DatePicker::make('invoice_date')->default(now())->required()->label('Tanggal Bulk Invoice'),
                TextInput::make('invoice_title')->label('Subject/Header')->required(),
                TextInput::make('invoice_item_description')->label('Deskripsi Item Bulk Invoice')->required(),
                TextInput::make('total_amount')->label('Jumlah Per Siswa')->required()->numeric()->suffix('IDR'),
                Select::make('status')->default('draft')->options([
                    'draft' => 'Draft',
                    'approved' => 'Approved',
                    'cancel' => 'Cancel',
                ])->visibleOn(['view'])
            ])->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Nomor Bulk Invoice'),
                TextColumn::make('invoice_date')->label('Tanggal Bulk Invoice')->date('d-M-Y'),
                TextColumn::make('invoice_title')->label('Subject/Header'),
                TextColumn::make('total_amount')->label('Jumlah Per Siswa')->money('IDR'),
                TextColumn::make('status')->label('Status')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BulkInvoiceMembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBulkInvoices::route('/'),
            'create' => Pages\CreateBulkInvoice::route('/create'),
            'edit' => Pages\EditBulkInvoice::route('/{record}/edit'),
            'view' => Pages\ViewBulkInvoice::route('/{record}'),
        ];
    }
}
