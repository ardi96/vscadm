<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;

class GeneratePreviousInvoice
{
    
    public function __invoke($year, $month)
    {

        // Define package prices as it may different
        // from current package prices in master data

        $price = [
            2 => 400000,
            3 => 300000,
            4 => 480000,
            5 => 350000,
            6 => 600000,
            10 => 450000,
            12 => 550000,
        ];

        $members = Member::whereNot('status','inactive')
                        ->get();

        foreach($members as $member)
        {
            $invoice = Invoice::where('member_id', $member->id)
                        ->where('type', 'membership')
                        ->where('invoice_period_year', $year)
                        ->where('invoice_period_month', $month)
                        ->first();

            $invoicePeriod = Date::create($year, $month, 1);

            if ( !$invoice )
            {
                try
                {
                    $invoice = Invoice::create([
                        'member_id' => $member->id,
                        'type' => 'membership',
                        'parent_id' => $member->parent->id,
                        'amount' => $price[$member->package->id] ?? 0,
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
                }
                catch( Exception $e)
                {
                    Log::error("Error generating invoice for member: " . $member->id . " - " . $e->getMessage());   
                }
            }

        }
    }
}