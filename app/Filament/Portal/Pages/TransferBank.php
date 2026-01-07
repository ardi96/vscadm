<?php

namespace App\Filament\Portal\Pages;

use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;

class TransferBank extends Page implements HasTable, HasForms 
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;

    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.portal.pages.transfer-bank';

    protected static bool $shouldRegisterNavigation = false;

    // invoice ID from query parameter, can be multiple separated by comma
    protected ?string $invoice_id;

    // array representation of invoice IDs
    protected array $invoice_ids = [];

    protected array $invoice_arr = [];

    public float $total_amount = 0.00;

    public ?array $data = []; 

    public $invoices ;


    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function mount()
    {
        $this->invoice_id = request()->get('id');
        
        if ( $this->invoice_id )
        {
            
            $this->invoice_ids = explode(',', $this->invoice_id);
            
            $this->total_amount = Invoice::whereIn('id', $this->invoice_ids)->where('parent_id', Auth::user()->id)->sum('amount');

            $this->invoices = Invoice::whereIn('id', $this->invoice_ids)->where('status','unpaid')->where('parent_id', Auth::user()->id)->get();
            
            $this->invoice_arr = Invoice::whereIn('id', $this->invoice_ids)->where('status','unpaid')->where('parent_id', Auth::user()->id)->get()->pluck('id')->toArray();

            $this->form->fill(['invoice_ids' => $this->invoice_arr]);

        }

    }

    public function table(Table $table): Table
    {
        $invoice_ids = $this->form->getRawState()['invoice_ids'];

        return $table
            ->query(Invoice::query()->whereIn('id', $invoice_ids)->where('status','unpaid')->where('parent_id', Auth::user()->id))
            ->columns([
                TextColumn::make('invoice_no')->label('No. Invoice'),
                TextColumn::make('member.name')->label('Atas Nama'),
                TextColumn::make('description')->label('Keterangan'),
                TextColumn::make('item_description')->label('Nama Paket'),
                TextColumn::make('amount')->label('Jumlah')->money('IDR'),
            ])
            ->emptyStateHeading('Tidak ada tagihan yang dipilih')
            ->paginated(false);
    }

    
    public function form(Form $form ): Form
    {
        return $form
            ->schema([

                Hidden::make('invoice_ids'),
                    
                FileUpload::make('file_name')
                    ->label('Upload Bukti Pembayaran')
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
                
                TextInput::make('notes')->label('Keterangan')->required(),
            ])
            ->statePath('data')
            ->columns(3);
    }

    public function proceedToPayment()
    {
        // process the uploaded payment proof and other data here
        $data = $this->form->getState();

        // after processing, redirect to a confirmation page or back to invoices
        $ids_string = implode(',', $this->invoice_arr);


        // we save the payment record here
        $payment = new \App\Models\Payment();
        $payment->user_id = Auth::user()->id;
        $payment->payment_date = $data['payment_date'];
        $payment->amount = $this->total_amount;
        $payment->bank = $data['bank'];
        $payment->notes = $data['notes'];
        $payment->file_name = $data['file_name'];
        $payment->status = 'pending';
        $payment->order_id = null;
        $payment->is_online = false;
        $payment->save();
        
        // link payment to invoices
        foreach ( $data['invoice_ids'] as $inv_id ) {
            $invoice = Invoice::find($inv_id);
            $payment->invoices()->attach($invoice);

            $invoice->status = 'pending';
            $invoice->save();
        }   

        return redirect('/portal/payments/'.$payment->id)->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi.');
    }

    protected function getFormActions(): array
    {
        return [

            Action::make('cancel')
                ->label('Cancel')
                ->alpineClickHandler('window.location.href = "/portal/invoices"')
                ->color('gray'),

            Action::make('proceedToPayment')
                ->label('Kirim Bukti Pembayaran')
                ->button()
                ->color('primary')
                ->submit('proceedToPayment')

        ];
    }

}
