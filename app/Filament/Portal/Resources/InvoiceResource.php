<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Member;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\InvoiceResource\Pages;
use App\Filament\Portal\Resources\InvoiceResource\Pages\OrderPage;
use App\Filament\Portal\Resources\InvoiceResource\RelationManagers;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    // protected static ?string $navigationGroup = 'Finance';
    
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
                TextColumn::make('invoice_no')->label('No. Invoice')->sortable()->searchable()
                    ->description(fn($record) => date_format(date_create($record->invoice_date),'d-M-Y') ),
                // TextColumn::make('invoice_date')->label('Tgl. Invoice')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('member.name')->label('Atas Nama')->searchable()->sortable(),
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

                    Tables\Actions\Action::make('upload_payment_proof')->icon('heroicon-m-banknotes')->label('Upload Bukti Bayar')
                        ->url(PaymentResource\Pages\CreatePayment::getUrl())
                        ->visible(fn($record) => $record->status == 'unpaid'),
                    
                    Tables\Actions\Action::make('pay_online')->icon('heroicon-m-banknotes')->label('Bayar Secara Online')
                        ->url(function($record) {
                            return '/portal/checkout-page?id='.$record->id;
                        })->visible(fn($record) => $record->status == 'unpaid' && env('ONLINE_PAYMENT_ENABLED',false)),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\BulkAction::make('Bayar Secara Online')
                        ->action(function (Collection $records) {
                            $ids = [];
                            foreach ($records as $record) {
                                $ids[] = $record->id;
                            }
                            $ids_string = implode(',', $ids);
                            return redirect('/portal/checkout-page?id='.$ids_string);
                        })
                        ->color('success')
                        ->icon('heroicon-m-check-circle'),
                    // Tables\Actions\DeleteBulkAction::make(),
                ])->label('Actions'),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Invoice $record): bool => $record->status == 'unpaid' && env('ONLINE_PAYMENT_ENABLED',false))
            ->defaultSort('invoice_no','desc')
            ->modifyQueryUsing(function (Builder $query) {

                $query->where('parent_id', Auth::user()->id)
                ->whereIn('member_id', Member::all()->pluck('id'));
                
                return $query;
            });
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
            // 'order' => Pages\OrderPage::route('/{record}/order'),
        ];
    }
}
