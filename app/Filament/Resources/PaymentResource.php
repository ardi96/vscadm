<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaymentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentResource\RelationManagers;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    // protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Bukti Pembayaran';

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
                TextColumn::make('payment_date')->label('Tanggal Pembayaran')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('amount')->label('Jumlah Pembayaran')->money('IDR')->searchable()->sortable(),
                TextColumn::make('notes')->label('Keterangan')->searchable()->sortable(),
                TextColumn::make('bank')->label('Nama Bank')->searchable()->sortable(),
                TextColumn::make('invoices.invoice_no')->label('No. Invoices')->bulleted()->searchable(),
                TextColumn::make('invoices.type')->label('Judul Invoices')->bulleted()->searchable(),
                TextColumn::make('invoices.description')->label('Keterangan Invoice')->bulleted()->searchable(),
                TextColumn::make('invoices.member.name')->label('Nama Anak')->bulleted()->searchable(),
                TextColumn::make('invoices.parent.name')->label('Nama Org. Tua')->bulleted()->searchable(),
                TextColumn::make('created_at')->label('Tanggal Upload')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('status')->label('status')->searchable()->sortable()
                ->badge()
                ->color(fn(string $state):string => match($state) {
                    'accepted' => 'primary',
                    'pending' => 'secondary',
                    'rejected' => 'danger',
                })
                ->icon(fn(string $state):string => match($state) {
                    'accepted' => 'heroicon-m-check-circle',
                    'pending' => 'heroicon-m-question-mark-circle',
                    'rejected' => 'heroicon-m-x-circle',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('lihat attachment')->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn(Payment $record):string => url('storage/'. $record->file_name))
                    ->openUrlInNewTab(),
                    // Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('id','desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('is_online', false)
                          ->orWhere(function ($query) {
                              $query->where('is_online', true)
                                    ->where('status', 'accepted');
                          })
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
