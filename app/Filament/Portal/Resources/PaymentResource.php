<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invoice;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\View\TablesRenderHook;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\PaymentResource\Pages;
use App\Filament\Portal\Resources\PaymentResource\RelationManagers;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Doctrine\DBAL\Schema\Column;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Riwayat Pembayaran';

    protected static ?int $navigationSort = 30;


    // protected static ?string $label = 'Pembayaran';

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')->label('Jumlah Pembayaran')
                    ->numeric()->suffix('Rupiah'
                    )->required()->default(
                        Invoice::where('parent_id',Auth::user()->id)
                                ->where('status','unpaid')->sum('amount')
                    ),
                DatePicker::make('payment_date')->label('Tanggal Pembayaran')->required()->default(Date::now()),
                TextInput::make('bank')->label('Nama Bank Anda')->required(),
                Textinput::make('notes')->label('Keterangan')->required(),
                CheckboxList::make('invoices')
                ->columnSpanFull()
                ->bulkToggleable()
                ->required()
                ->label('Pembayaran untuk invoice')
                ->relationship('invoices','invoice_no')
                ->options(
                    DB::table('invoices')
                        ->join('members','invoices.member_id','=','members.id')
                        // ->select('invoices.id, invoices.no_incoive, invoices.amount, invoices.item_description, members.name')
                        ->where('invoices.parent_id',Auth::user()->id)
                        ->where('invoices.status','unpaid')
                        ->select(DB::raw(' concat( \'No. Invoice: \', invoices.invoice_no, \' , Nominal : \', format(invoices.amount,2), \', Keterangan: \', invoices.item_description, \', Atas Nama: \', members.name) as no, invoices.id as id '))
                        ->pluck('no','id')),

                FileUpload::make('file_name')->label('Upload Bukti Pembayaran')
                    ->required()
                    ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                    ->maxSize(1024*2),
            ]);
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
                        ->openUrlInNewTab(),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::user()->id));
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
            // 'edit' => Pages\EditPayment::route('/{record}'),
        ];
    }
}
