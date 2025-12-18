<?php

namespace App\Filament\Pages;

use App\Models\IuranBulananMember;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Services\InvoiceService;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\Actions;
use Filament\Forms\Contracts\HasForms;
use App\Services\PeriodDropdownService;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\CheckboxList;

class CreateMultiPeriodInvoice extends Page implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.create-multi-period-invoice';
    protected static ?string $title = 'Buat Invoice Multi Periode';
    protected static ?string $navigationLabel = 'Invoice Multi Periode';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 20;
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('member_id')->label('Pilih Member')->required()
                ->options(function () {
                    return \App\Models\Member::all()->pluck('name', 'id');
                })
                ->searchable(),
            TextInput::make('amount')->required()->label('Total Invoice')->suffix('IDR')->numeric(),
            Select::make('period_from')->label('Dari Periode')->required()
                ->options(
                    PeriodDropdownService::getPeriodOptions(0, 6),
                ),
            Select::make('period_to')->required()->label('Sampai Periode')
                ->options(
                    PeriodDropdownService::getPeriodOptions(0, 12),
                ),
            // CheckboxList::make('periods')
            //     ->options(
            //         PeriodDropdownService::getPeriodOptions(-1, 12),
            //     )->label('Pilih Periode Invoice')->columns(2),
        ])
        ->columns(2)
        ->statePath('data');
    }


    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Save')
                ->button()
                ->color('primary')
                ->submit('Save'),
        ];
    }

    public function save()
    {
        try {

            $data = $this->form->getState();

            $current = strtotime($data['period_from']);
            $end = strtotime($data['period_to']);

            if ($current > $end) {
                throw new Halt('Pilihan periode tidak valid. Silakan pilih kembali.');
            }


            // check if there is existing invoice for the member in the selected periods
            
            while ($current <= $end) {
                
                // check dari table Invoice
                $existingInvoice = \App\Models\Invoice::where('member_id', $data['member_id'])
                    ->where('type', 'membership')
                    ->where('invoice_period_year', date('Y', $current))
                    ->where('invoice_period_month', date('m', $current))
                    ->whereNot('status', 'void')
                    ->first();      

                if ($existingInvoice) {
                    throw new Halt('Invoice sudah ada untuk member pada periode ' . date('M-Y', $current) . '. Silakan pilih periode lain.');
                }
                
                // check dari table IuranBulananMember
                $existingInvoice = IuranBulananMember::where('member_id', $data['member_id'])
                    ->where('period_year', date('Y', $current))
                    ->where('period_month', date('m', $current))
                    ->whereNot('status', 'void' )
                    ->first();
                if ($existingInvoice) {
                    throw new Halt('Invoice sudah ada untuk member pada periode ' . date('M-Y', $current) . '. Silakan pilih periode lain.');
                }

                $current = strtotime('+1 month', $current);
            }   

            // reset current to period_from
            $current = strtotime($data['period_from']);
            $end = strtotime($data['period_to']);
            
            $invoice = InvoiceService::createInvoice(
                member: Member::find($data['member_id']),
                amount: $data['amount'],
                invoiceDate: now(),
                description: 'Iuran Bulanan (multiple)',
                itemDescription: 'Iuran bulanan periode ' . date('M-Y', strtotime($data['period_from'])) . ' s.d. ' . date('M-Y', strtotime($data['period_to'])),                
                invoiceType: 'other'
            );

            // gernerate IuranBulananMember records

            while ($current <= $end) {
             
                $iuran = new IuranBulananMember();
                $iuran->member_id = $data['member_id'];
                $iuran->invoice_id = $invoice->id;
                $iuran->period_year = date('Y', $current);
                $iuran->period_month = date('m', $current);
                $iuran->status = 'unpaid';
                $iuran->save();

                $current = strtotime('+1 month', $current);
            }

            Notification::make()
                ->title('Invoice created successfully!')
                ->success()
                ->send();

            return redirect()->route('filament.admin.resources.invoices.index');
        }
        catch(Halt $exception)
        {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
            return;
        }
    }

    
}
