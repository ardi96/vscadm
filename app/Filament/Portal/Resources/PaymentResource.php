<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Doctrine\DBAL\Schema\Column;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\View\TablesRenderHook;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\ImageEntry;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\PaymentResource\Pages;
use App\Filament\Portal\Resources\PaymentResource\RelationManagers;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Riwayat Pembayaran';

    protected static ?int $navigationSort = 30;


    // protected static ?string $label = 'Pembayaran';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('payment_date')->label('Tanggal Pembayaran')->formatStateUsing(fn ($state) => date_format(date_create($state), 'd-M-Y')),
                TextEntry::make('bank')->label('Nama Bank'),
                TextEntry::make('amount')->label('Jumlah Pembayaran')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' Rupiah'),
                TextEntry::make('notes')->label('Keterangan'),
                TextEntry::make('order_id')->label('Order Reference (online payment)'),
                TextEntry::make('status')->label('Status'),
                TableRepeatableEntry::make('invoices')
                    ->label('Untuk Pembayaran Invoice')
                    ->schema([
                        TextEntry::make('invoice_no')->label('No. Invoice'),
                        TextEntry::make('description')->label('Keterangan'),
                        TextEntry::make('amount')->label('Jumlah')->money('IDR'),
                    ])->columnSpanFull(),
                ImageEntry::make('file_name')->label('Bukti Pembayaran')->disk('public'),
            ]);
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('info')->label('')
                    ->content(new HtmlString()),
                    
                FileUpload::make('file_name')->label('Upload Bukti Pembayaran')
                    ->required()
                    ->columnSpanFull()
                    ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                    ->maxSize(1024*2),
    
                Placeholder::make('instruction')->label('')->columnSpanFull()
                    ->content(new HtmlString('<b>BRI</b> 043901001248566 a.n. VEINS SKATING CLUB<br><b>BCA</b> 3429243999 a.n. PERKUMPULAN VEINS SKATING')),
                
                Placeholder::make('note')->label('')->columnSpanFull()
                    ->content(new HtmlString('Mohon mention berita/keterangan nama ananda di pembayaran anda.')),
                
                TextInput::make('bank')->label('Nama Bank Anda')->required(),
                
                DatePicker::make('payment_date')->label('Tanggal Pembayaran')->required()->default(Date::now()),
                
                Textinput::make('notes')->label('Keterangan')->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_date')->label('Tanggal Pembayaran')->date('d-M-Y')->searchable()->sortable(),
                TextColumn::make('amount')->label('Jumlah Pembayaran')->money('IDR')->searchable()->sortable(),
                TextColumn::make('bank')->label('Nama Bank')->searchable()->sortable(),
                TextColumn::make('notes')->label('Keterangan')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Tanggal Upload')->searchable()->sortable(),
                TextColumn::make('status')->label('status')
                    ->searchable()->sortable()
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
                    Tables\Actions\Action::make('payment_link')->label('Link Pembayaran')->icon('heroicon-m-credit-card')
                    ->url(fn(Payment $record):string => $record->payment_url? : '#')
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->is_online && $record->status == 'pending'),
                    // Tables\Actions\EditAction::make(),   
                    Tables\Actions\ViewAction::make(),
                    
                    Tables\Actions\Action::make('lihat attachment')->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn(Payment $record):string => url('storage/'. $record->file_name))
                        ->openUrlInNewTab(),
                    
                    // Tables\Actions\DeleteAction::make()->visible(fn($record) => $record->status == 'pending'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id','desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::user()->id)
                ->where( function ($query) {
                    $query->where('is_online', false)
                          ->orWhere(function ($query) {
                              $query->where('is_online', true)
                                    ->where('status', 'accepted');
                          });
                }));
    }

    public static function getRelations(): array
    {
        return [
          
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            // 'edit' => Pages\EditPayment::route('/{record}/edit'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
