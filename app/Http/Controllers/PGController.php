<?php

namespace App\Http\Controllers;

use Dompdf\FrameDecorator\Page;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PGController extends Controller
{
    //Payment Gateway Notification Handlers

    public function notifications(Request $request)
    {
        // Handle successful payment notification
        // Validate the request that it's coming from the payment gateway
        // Update the payment status in the database
        // Send confirmation email to the user

        $server_key = env('MIDTRANS_SERVER_KEY');

        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $server_key);
 
        if ($hashed != $request->signature_key) {
            Log::warning('Payment Notification: Invalid Signature', $request->all());
            return response()->json(['status' => 'error', 'message' => 'Invalid Signature'], 403);
        }

        $payment = Payment::where('order_id', $request->order_id)->first();

        if ($payment) {

            if ( $request->transaction_status == 'settlement' || $request->transaction_status == 'capture' ) {
                // Payment is successful
                // Update payment status to accepted
                $payment->update([
                    'status' => 'accepted',
                    'payment_date' => now(),
                ]);

                // Handle post-payment processes like updating invoices, sending emails, etc.
                $this->handlePostPayment($payment);
                
            } elseif ( in_array($request->transaction_status, ['deny', 'expire', 'cancel']) ) {
                // Payment failed or expired
                // Update payment status to rejected and update invoice status to 'unpaid'
                $this->cancelPayment($payment);

            } elseif ( $request->transaction_status == 'pending' ) {
                // Payment is pending
                // Update payment status to pending
                $payment->update([
                    'status' => 'pending',
                    'payment_date' => now(),
                ]);
            }
        
        }

        return response()->json(['status' => 'success'], 200);
    }


    public function receiptPage(Request $request)
    {
        $message_header = '';
        $message_body = '';

        //order_id=VSC25110715423956&status_code=200&transaction_status=settlement
        $order_id = $request->query('order_id');
        $status_code = $request->query('status_code');
        $transaction_status = $request->query('transaction_status');    

        if ( $order_id && $status_code && $transaction_status ) {
            if ( $status_code == '200' && $transaction_status == 'settlement' ) {
                $message_header = 'Payment Successful !';
                $message_body = 'Thank you for your payment. Your transaction has been completed successfully. Order ID: '. $order_id;
            } else {
                $message_header = 'Payment Failed';
                $message_body = 'Unfortunately, your payment was not successful. Please try again or contact support.';
            }
        } else {
            $message_header = 'Payment Not Found';
            $message_body = 'We could not find any payment information. Please contact support if you believe this is an error.';
        }
        // Render a success page to the user after payment
        
        return view('payment_received',[
            'message_header' => $message_header,
            'message_body' => $message_body
        ]);
    }

    public function cancelPayment(Payment $payment)
    {
        // Handle payment cancellation logic
        $payment->update([
            'status' => 'rejected',
            'payment_date' => now(),
        ]);

        $invoices = $payment->invoices;

        foreach ( $invoices as $invoice ) {            
            $invoice->cancelPayment();
        }

    }

    public function handlePostPayment(Payment $payment)
    {
        // Handle post-payment processes like updating invoices, sending emails, etc.

        $invoices = $payment->invoices;
        
        foreach ( $invoices as $invoice ) {
            
            $invoice->payNow();

            // based on invoice type, handle different scenarios : 
            // if it's registraion fee, activate the user account, etc.
            // if it's a leave request, approve the leave, etc.

        }

    }

}
