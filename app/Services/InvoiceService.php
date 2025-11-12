<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use App\Jobs\SendInvoiceMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DateTime;

class InvoiceService
{
    /**
     * Given: member_id, 
     * Output: current month invoice
     */

    public static function getNextNumber() : int
    {
        return (Invoice::max('id')) + 1024;
    }


    public static function createInvoice(Member $member, Float $amount, string $invoiceDate, 
        string $description, string $itemDescription, string $invoiceType='other') : Invoice
    {
        $invoice = Invoice::create([
            'member_id' => $member->id,
            'type' => $invoiceType,
            'parent_id' => $member->parent->id,
            'amount' => $amount,
            'invoice_date' => $invoiceDate,
            'invoice_no' => config('payment.invoice_prefix','VSC') . self::getNextNumber(),
            'description' => $description,
            'item_description' => $itemDescription,
            'status' => 'unpaid',
        ]);

        $member->balance += $invoice->amount;
        $member->last_invoice_date = $invoiceDate;
        $member->save();

        // create invoice item 
        $invoice->items()->create([
            'description' => $itemDescription,
            'amount' => $amount
        ]);

        return $invoice;
    }
    

    /**
     * Generate invoice for member
     * Item 1: Membership fee (normal invoice)
     * Item 2: Check if any additional session for this member
     * Get the details : date and coach.
     * Amount is calculated based on the field : price_per_session
     * Sum the total of items
     */
    public static function generate(Member $member, Carbon $invoicePeriod) : ?Invoice
    {
        // $from = date_create( Date::now()->format('Y-m-01') );

        // $to = date_sub( $from, date_interval_create_from_date_string("1 day") );
        
        // $from = date_create( $to->format('Y-m-01') );
        
        // logger('From: ' . $from->format('Y-m-d'));

        // logger('To: ' . $to->format('Y-m-d'));
        
        // create Invoice Header

        // $invoicePeriod = date_create( Date::now()->format('Y-m-t') );

        // $invoicePeriod = date_add($invoicePeriod, date_interval_create_from_date_string("1 day"));

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'type' => 'membership',
            'parent_id' => $member->parent->id,
            'amount' => $member->package->price,
            'invoice_date' => Date::now(),
            'invoice_no' => config('payment.invoice_prefix','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Membership Fee '. $invoicePeriod->format('M-Y'),
            'item_description' => $member->package->name,
            'status' => 'unpaid',
            'invoice_period_year' => $invoicePeriod->year,
            'invoice_period_month' => $invoicePeriod->month,

        ]);

        $member->balance = $member->balance + $invoice->amount;
        $member->last_invoice_date = Date::now();
        $member->save();

        // create invoice item 
        $invoice->items()->create([
            'description' => 'Membership Fee Bulan '. $invoicePeriod->format('M-Y'),
            'amount' => $member->package->price
        ]);


        // if Flat Rate, we don't calculate additional session fee
        //
        if ( $member->package->is_flat ) {
         
            return $invoice;
        
        }

        /* 23/8/2025 : we implement Flat Rate based on Om Ade Whatsapp */

        // $kehadiran = $member->getAttendanceCount($from->format('Y-m-d'), $to->format('Y-m-d') );

        // logger('Kehadiran: ' . $kehadiran ."\r\n");

        // $last_month_from = date_sub($from, date_interval_create_from_date_string("1 month"));
        
        // $last_month_to = date_create( $last_month_from->format('Y-m-t') );  

        // $carried_forward = $member->getCarriedForwardHoliday($last_month_from->format('Y-m-d'), $last_month_to->format('Y-m-d'));
        
        // logger('Carried Forward: ' . $carried_forward  ."\r\n");


        /* 23/8/2025 : we implement Flat Rate based on Om Ade Whatsapp */


        // if(( $kehadiran - $carried_forward ) > $member->package->session_per_week)
        // {
            
        //     $additional = $kehadiran - $carried_forward - $member->package->session_per_week;

        //     $additional_amount = $additional * $member->package->price_per_session;
            
        //     $invoice->items()->create([
        //         'description' => $additional . ' sesi tambahan ' . $to->format('M-Y'),
        //         'amount' => $additional_amount
        //     ]);

        //     $invoice->amount = $invoice->amount + $additional_amount;

        //     $invoice->save();

        //     $member->balance = $member->balance + $additional_amount;
        //     $member->save();
        // }

        // SendInvoiceMail::dispatch($invoice);
        
        return $invoice;
    }

    public static function generateRegistrationInvoice(Member $member, Payment $payment) : ?Invoice
    {

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'type' => 'registration',
            'parent_id' => $member->parent->id,
            'amount' => $payment->amount,
            'invoice_date' => Date::now(),
            'invoice_no' => config('payment.invoice_prefix','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Registration Fee',
            'item_description' => $member->package->name,
            'status' => 'pending'
        ]);

        $member->balance = $member->balance + $invoice->amount;
        $member->last_invoice_date = Date::now();
        $member->save();

        $invoice->items()->create([
            'description' => 'Registration Fee',
            'amount' => $payment->amount
        ]);

        SendInvoiceMail::dispatch($invoice);
        
        return $invoice;
    }

    public static function generateRegistrationInvoice2(Member $member, int $amount) : ?Invoice
    {

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'type' => 'registration',
            'parent_id' => $member->parent->id,
            'amount' => $amount,
            'invoice_date' => Date::now(),
            'invoice_no' => config('payment.invoice_prefix','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Registration Fee',
            'item_description' => $member->package->name,
            'status' => 'unpaid'
        ]);

        $member->balance = $member->balance + $amount;
        $member->last_invoice_date = Date::now();
        $member->save();

        $invoice->items()->create([
            'description' => 'Registration Fee',
            'amount' => $amount
        ]);

        SendInvoiceMail::dispatch($invoice);
        
        return $invoice;
    }

}