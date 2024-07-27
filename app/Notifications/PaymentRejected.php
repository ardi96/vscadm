<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Payment $payment)
    {
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
                    ->subject('Bukti Pembayaran Ditolak')
                    ->line('Bukti pembayaran Anda tanggal ' . date_format(date_create($this->payment->payment_date),'d-M-Y') .' untuk ' . $this->payment->notes . ' tidak dapat diverifikasi.')
                    ->line('Silakan mengupload ulang melalui halaman portal Veins Skating Club.')
                    ->action('Klik di sini untuk login', url('/portal/payments'))
                    ->line('Terima Kasih.');
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
