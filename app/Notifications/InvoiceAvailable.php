<?php

namespace App\Notifications;

use App\Filament\Portal\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceAvailable extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Invoice $invoice)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Tagihan bulan ini telah tersedia.')
                    ->line('No. Invoice: '. $this->invoice->invoice_no)
                    ->line('Tgl. Invoice: '. date_format(date_create($this->invoice->invoice_date),'d-M-Y'))
                    ->line('Jumlah: IDR '. number_format($this->invoice->amount,0,',','.'))
                    ->line('Nama Anggota: '. $this->invoice->member->name)
                    ->line('Nama Paket: '. $this->invoice->item_description)
                    ->action('Check Invoice Di Sini', url('/portal/invoices'))
                    ->line('Terima Kasih');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
