<?php

namespace App\Filament\Portal\Pages;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;

class CheckoutPage extends Page implements HasTable, HasForms 
{

    use \Filament\Tables\Concerns\InteractsWithTable;
    use \Filament\Forms\Concerns\InteractsWithForms;
    

    protected static string $view = 'filament.portal.pages.checkout-page';

    protected static bool $shouldRegisterNavigation = false;

    // invoice ID from query parameter, can be multiple separated by comma
    protected ?string $invoice_id;

    // array representation of invoice IDs
    protected array $invoice_ids = [];

    public float $total_amount = 0.00;

    public ?array $data = []; 

    public ?array $invoice_arr = [];

    public function mount()
    {
        $this->invoice_id = request()->get('id');
        
        if ( $this->invoice_id )
        {

            $this->invoice_ids = explode(',', $this->invoice_id);
            
            $this->total_amount = Invoice::whereIn('id', $this->invoice_ids)->where('parent_id', Auth::user()->id)->sum('amount');

            $this->invoice_arr = Invoice::whereIn('id', $this->invoice_ids)->where('parent_id', Auth::user()->id)->get()->pluck('id')->toArray();

            $this->form->fill( ['invoice_ids' => $this->invoice_arr] );

        }

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('invoice_ids'),
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Invoice::query()->whereIn('id', $this->invoice_ids)->where('parent_id', Auth::user()->id))
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

    public function proceedToPayment()
    {
        $invoices = Invoice::whereIn('id', $this->data['invoice_ids'])->where('parent_id', Auth::user()->id)->get();
        
        $order_id = MidtransService::generateOrderId();

        $items = [];

        foreach ( $invoices as $invoice ) {
            $items[] = MidtransService::ConvertInvoicetoItem($invoice);
        }

        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $this->total_amount,
        ];

        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $payment_url = MidtransService::checkout(
            null,
            $transaction_details,
            $items,
            $customer_details
        );

        if ( $payment_url == null ) {
            Notification::make()
                ->title('Gagal memproses pembayaran. Silakan coba lagi.')
                ->danger()
                ->send();
            return;
        }

        // create a payment record in the database
        $payment = Payment::create([
            'is_online' => true,
            'order_id' => $order_id,
            'payment_url' => $payment_url,
            'payment_date' => now(),
            'amount' => $this->total_amount,
            'status' => 'pending',
            'bank' => 'Midtrans',
            'notes' => 'Online Payment',
            'user_id' => Auth::user()->id,
        ]);

        // link payment to invoices
        foreach ( $invoices as $invoice ) {

            PaymentInvoice::create([
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
            ]);

            $invoice->status = 'pending';
            $invoice->save();
            
        }
        
        return redirect()->to($payment_url);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('proceedToPayment')
                ->label('Lanjut ke Pembayaran')
                ->button()
                ->color('primary')
                ->submit('proceedToPayment')
                ->visible(config('payment.online_payment_enabled')),

                Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->alpineClickHandler('window.history.back()')
            ->color('gray')
        ];
    }
}
