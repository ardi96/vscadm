<?php

namespace App\Services;

use Midtrans\Config;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class ProcessExpiredPayments
{
    public function __invoke()
    {
        Log::info('Starting ProcessExpiredPayments job');

        $expiredPayments = Payment::where('status', 'pending')->where('is_online', true)
            ->get();

        foreach ($expiredPayments as $payment) {

            $status = MidtransService::inquiryPaymentStatus( $payment->order_id );

            if ( $status != 'expire' ) {
                continue;
            }   

            $payment->status = 'rejected';
            $payment->rejection_note = 'Payment expired automatically';
            $payment->save();

            foreach( $payment->invoices as $invoice)
            {
                $invoice->cancelPayment();
            }
        }
    }
}