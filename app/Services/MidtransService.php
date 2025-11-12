<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Leave;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use App\Models\GlobalParameter;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    // Midtrans related services can be implemented here


    /**
     * Checkout process using Midtrans
     * IS: user has selected items and ready to checkout
     * FS: transaction is created in Midtrans and payment URL is returned
     * @param Member $member
     * @param array $transactionDetails 
     * @param array $itemDetails
     * @param array $customerDetails
     * @return string $paymentUrl   
     */
    public static function checkout(?Member $member, array $transactionDetails, array $itemDetails, array $customerDetails)
    {
        // Implement Midtrans checkout process here

        Config::$serverKey = config('payment.midtrans.server_key');
        Config::$isProduction = config('payment.midtrans.is_production', false);
        Config::$isSanitized = true;
        
        
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
        
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => config('app.url') . '/portal/payment/received',
            ]
        ];

        $paymentUrl = Snap::getSnapUrl( $params );

        return $paymentUrl;
    }

    public static function ConvertInvoicetoItem(Invoice $invoice)
    {
        return [
            'id' => $invoice->invoice_no,
            'price' => $invoice->amount,
            'quantity' => 1,
            'name' => $invoice->invoice_no .' - '. $invoice->item_description .'. a.n. ' . $invoice->member->name,
        ];
    }

    public static function generateOrderId()
    {
        return 'VSC'.date('ymdHis').rand(10,99);
    }

    public static function restartPayment(Payment $payment)
    {
        
        $order_id  = $payment->order_id;
        $invoices = $payment->invoices;
        $items = [];

        foreach ( $invoices as $invoice ) {
            $items[] = MidtransService::ConvertInvoicetoItem($invoice);
        }
        
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $payment->amount,
        ];

        $customer_details = [
            'first_name' => $payment->user->name,
            'email' => $payment->user->email,
        ];  
        
        $payment_url = MidtransService::checkout(
            null,
            $transaction_details,
            $items,
            $customer_details
        );

        return $payment_url;
    }

    public static function inquiryPaymentStatus( $order_id )
    {
        Config::$serverKey = config('payment.midtrans.server_key');
        Config::$isProduction = config('payment.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $status = \Midtrans\Transaction::status($order_id);

        Log::info('Midtrans Inquiry Status for Order ID ' . $order_id . ': ' . json_encode($status));
        
        return $status->transaction_status;
    }

}