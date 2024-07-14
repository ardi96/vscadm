<?php

namespace App\Services;

use App\Models\Member;

class GenerateMonthlyInvoice
{
    public function __invoke()
    {
        $members = Member::whereNot('status','inactive')->get();

        foreach($members as $member)
        {
            InvoiceService::generate( $member );
        }
    }
}