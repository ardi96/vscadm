<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Forms;
use Filament\Tables;
use App\Models\Leave;
use Filament\Actions;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Tables\Actions\Action;
use Illuminate\Support\Str;
use App\Jobs\SendInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Forms\Components\TextInput;
use App\Jobs\GenerateInvoiceJob;
use App\Services\InvoiceService;
use Filament\Infolists\Infolist;
use App\Models\IuranBulananMember;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\FormsComponent;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use App\Filament\Resources\MemberResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class MemberInvoices extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    protected static string $relationship = 'invoices';

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';


    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')->required()->label('Judul Invoice'),
                Forms\Components\TextInput::make('item_description')->required()->label('Keterangan'),
                Forms\Components\TextInput::make('amount')->required()->label('Jumlah')->prefix('Rp. '),
                TableRepeater::make('items')
                    ->schema([
                        Forms\Components\TextInput::make('description')->required()->label('Deskripsi'),
                        Forms\Components\TextInput::make('amount')->required()->label('Jumlah')->numeric()->prefix('Rp. '),
                    ])->label('')
                    ->relationship('items')
                    ->addActionLabel('Tambah Item')
                    , 
                Forms\Components\Hidden::make('parent_id')->default($this->getRecord()->parent_id),
                Forms\Components\Hidden::make('invoice_no')->default(config('payment.invoice_prefix','VSC') . InvoiceService::getNextNumber()),
                Forms\Components\Hidden::make('type')->default('other'),
                Forms\Components\Hidden::make('status')->default('unpaid'),
                Forms\Components\Hidden::make('invoice_date')->default(Date::now()),
            ]);
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->label('No. Invoice')->alignCenter()->sortable()->searchable(),
                TextColumn::make('type')->label('Tipe Invoice')->searchable()->sortable(),
                TextColumn::make('invoice_period_year')->label('Periode')->searchable()->sortable()->alignCenter()
                    ->formatStateUsing(function (Invoice $record)  {

                        if ( !$record->invoice_period_year || !$record->invoice_period_month ) {
                            return '-';
                        }

                        $period = Date::createFromFormat('Y-m-d', $record->invoice_period_year.'-'.$record->invoice_period_month.'-01')
                            ->format('M-Y');
                        
                        return $period;
                    }),
                TextColumn::make('invoice_date')->date('d-M-Y')->label('Tanggal Invoice')->searchable()->sortable(),
                TextColumn::make('description')->label('Judul Invoice')->searchable()->sortable()->wrap(),
                TextColumn::make('item_description')->label('Paket')->searchable()->sortable(),
                TextColumn::make('amount')->money('IDR')->label('Jumlah')->searchable()->sortable(),
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
                })
            ])
            ->poll('10s')
            ->headerActions([
                Tables\Actions\CreateAction::make('Create New Invoice')
                    ->label('Create New Invoice')
                    ->after(function(Invoice $record) {
                        $member = $this->getOwnerRecord();
                        $amount = $record->amount;
                        $balance = $member->balance + $amount;
                        $member->update(['balance' => $balance]);
                    }),

                Tables\Actions\Action::make('Generate Monthly Invoice')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->required(),
                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(function() {
                                $currentYear = Date::now()->year;
                                return [
                                    $currentYear - 1 => $currentYear - 1,
                                    $currentYear => $currentYear,
                                    $currentYear + 1 => $currentYear + 1,
                                ];
                            })
                            ->required(),
                        // Forms\Components\DatePicker::make('period')
                        //     ->label('Periode Invoice')
                        //     ->helperText('Pilih tanggal mana saja untuk periode bulan & tahun invoice ini')
                        //     ->default(Date::now()->startOfMonth()->addMonth())
                        //     ->required(),
                    ])
                    ->action(function( array $data ) {

                       $period = Date::createFromFormat('Y-m-d', $data['year'].'-'.$data['month'].'-01');

                       $invoice = Invoice::where('member_id', $this->getRecord()->id)
                                        ->where('type', 'membership')
                                        ->whereNot('status','void')
                                        ->where('invoice_period_year', $data['year'])
                                        ->where('invoice_period_month', $data['month'])->first();

                        $iuranBulananMember = IuranBulananMember::where('member_id', $this->getRecord()->id)
                                        ->where('period_year', $data['year'])
                                        ->where('period_month', $data['month'])
                                        ->whereNot('status', 'void')
                                        ->first();

                        $leaves = Leave::where('member_id', $this->getRecord()->id)
                                    ->where('status', 1)
                                    ->where('start_date', '<=', $period)
                                    ->where('end_date', '>=', $period)
                                    ->first();

                        if ( !$invoice  && !$leaves && !$iuranBulananMember )
                        {
                            InvoiceService::generate($this->getRecord(), $period);
                        }
                        else
                        {
                            Notification::make()
                                ->title('Gagal Generate Invoice')
                                ->body('Terdapat invoice aktif pada periode yang dipilih atau member sedang cuti. Silakan void invoice terlebih dahulu sebelum mengenerate invoice baru.')
                                ->danger()
                                ->send();
                       }
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


                    Tables\Actions\Action::make('pdf') 
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Invoice $record) {
                        
                        // dd(Blade::render('invoice', ['record' => $record]));

                        File::ensureDirectoryExists(storage_path('app/public/invoices'));

                        $pdf = Pdf::loadView('invoice', ['record' => $record ]);

                        $filename = Str::uuid() . '.pdf';

                        $pdf->save(storage_path('app/public/invoices/') . $filename);
                        
                        return response()->download(storage_path('app/public/invoices/') . $filename);

                        // return response()->streamDownload(function () use ($record) {
                        //     echo Pdf::loadHtml(
                        //         Blade::render('invoice', ['record' => $record])
                        //     )->stream();
                        // }, $record->invoice_no . '.pdf');
                    }), 

                ])
            ])
            ->defaultSort('invoice_no','desc');;
    }
}
