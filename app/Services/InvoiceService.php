<?php

namespace App\Services;

use App\Models\User;
use App\Models\Leave;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use App\Jobs\SendInvoiceMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use App\Notifications\MemberAccepted;
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
        
        $amount = $member->package->price;

        $status = 'unpaid';

        $beasiswa = $member->beasiswas()
                        ->where('start_date', '<=', $invoicePeriod->format('Y-m-01'))
                        ->where('end_date', '>=', $invoicePeriod->format('Y-m-01'))
                        ->where('status', 1) // approved
                        ->latest()->first();
        
        if ( $beasiswa != null )
        {
            $amount = $beasiswa->biaya;

            if ( $amount == 0 )
            {
                $status = 'paid';
            } 
        }

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'type' => 'membership',
            'parent_id' => $member->parent->id,
            'amount' => $amount,
            'invoice_date' => Date::now(),
            'invoice_no' => config('payment.invoice_prefix','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Membership Fee '. $invoicePeriod->format('M-Y'),
            'item_description' => $member->package->name,
            'status' => $status,
            'invoice_period_year' => $invoicePeriod->year,
            'invoice_period_month' => $invoicePeriod->month,

        ]);

        $member->balance = $member->balance + $invoice->amount;
        $member->last_invoice_date = Date::now();
        $member->save();

        // create invoice item 
        $invoice->items()->create([
            'description' => 'Membership Fee Bulan '. $invoicePeriod->format('M-Y'),
            'amount' => $amount
        ]);


        // create record in IuranBulananMember
        $iuran = new \App\Models\IuranBulananMember();
        $iuran->member_id = $member->id;
        $iuran->invoice_id = $invoice->id;
        $iuran->period_year = $invoicePeriod->year;
        $iuran->period_month = $invoicePeriod->month;
        $iuran->status = $status;
        $iuran->save();
        
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

    public static function handlePostPayment( Invoice $invoice)
    {
        if ( $invoice->type == 'registration')
            {
                $member = $invoice->member;

                $member->status = 'active';
                $member->save();

                $user = User::find($member->parent_id);
                $user->notify(new MemberAccepted( $member ));    
            }
            elseif ( $invoice->type == 'leave')
            {
                $member = $invoice->member;

                $leave = Leave::where('member_id', $member->id)->where('status','pending')->get()->last();
                $leave->status = 1;
                $leave->save();
                
            }
    }
}