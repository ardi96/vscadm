<?php

namespace App\Services;

use Midtrans\Config;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class ProcessExpiredPayments
{
    public function __invoke()
    {
        $expiredPayments = Payment::where('status', 'pending')->where('is_online', true)
            ->get();

        foreach ($expiredPayments as $payment) {
            try
            {
                $status = MidtransService::inquiryPaymentStatus( $payment->order_id );

                if ( $status != 'expire' ) {

                    if ( $status == 'settlement' ) {
                        $payment->status = 'accepted';
                        $payment->save();

                        foreach( $payment->invoices as $invoice)
                        {
                            $invoice->payNow();
                        }
                    }

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
            catch ( \Exception $e )
            {
                Log::error('Error processing payment ID ' . $payment->id . ': ' . $e->getMessage() );
                continue;
            }
        }
    }
}