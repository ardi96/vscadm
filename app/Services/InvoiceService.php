<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Date;

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

    public static function generate(Member $member) : ?Invoice
    {

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'parent_id' => $member->parent->id,
            'amount' => $member->package->price,
            'invoice_date' => Date::now(),
            'invoice_no' => env('INVOICE_PREFIX','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Membership Fee '. Date::now()->format('M-Y'),
            'item_description' => $member->package->name,
            'status' => 'unpaid',
        ]);

        $member->balance = $member->balance + $invoice->amount;
        $member->last_invoice_date = Date::now();
        $member->save();

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
            'invoice_no' => env('INVOICE_PREFIX','VSC') . InvoiceService::getNextNumber(),
            'description' => 'Registration Fee',
            'item_description' => $member->package->name,
            'status' => 'pending'
        ]);

        $member->balance = $member->balance + $invoice->amount;
        $member->last_invoice_date = Date::now();
        $member->save();

        return $invoice;
    }

}