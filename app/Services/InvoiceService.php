<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Support\Facades\Date;

class InvoiceService
{
    /**
     * Given: member_id, 
     * Output: current month invoice
     */

    public static function getNextNumber() : int
    {
        return (Invoice::max('id')) + 1;
    }

    public static function generate(Member $member) : ?Invoice
    {
        $invoice = Invoice::create([
            'member_id' => $member->id,
            'amount' => $member->package->price,
            'invoice_date' => Date::now(),
            'invoice_no' => InvoiceService::getNextNumber(),
            'description' => 'Membership Fee',
            'item_description' => $member->package->name,
        ]);

        return $invoice;
    }

}