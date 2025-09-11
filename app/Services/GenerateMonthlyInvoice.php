<?php

namespace App\Services;

use Exception;
use App\Models\Leave;
use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;

class GenerateMonthlyInvoice
{
    public function __invoke()
    {
        $members = Member::whereNot('status','inactive')->whereNot('status','resigned')->get();

        $period = Date::now()->startOfMonth()->addMonth();

        $invoice_period_year = $period->year;
        $invoice_period_month = $period->month;

        Log::info("Generating invoice for period: " . $invoice_period_year . '-' . $invoice_period_month );
        
        foreach($members as $member)
        {

            $leaves = Leave::where('member_id', $member->id)
                        ->where('status', 1)
                        ->where('start_date', '<=', $period)
                        ->where('end_date', '>=', $period)
                        ->first();

            $invoice = Invoice::where('member_id', $member->id)
                        ->where('type', 'membership')
                        ->where('invoice_period_year', $invoice_period_year)
                        ->where('invoice_period_month', $invoice_period_month)->first();

            if ( !$invoice && !$leaves )
            {
                try
                {
                    InvoiceService::generate( $member, $period);
                }
                catch( Exception $e)
                {
                    Log::error("Error generating invoice for member: " . $member->id . " - " . $e->getMessage());   
                }
            }
            else
            {
                Log::info("Skipping invoice for member: " . $member->id . " - Invoice exists or on leave");
            }

        }
    }
}