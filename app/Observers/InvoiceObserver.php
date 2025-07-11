<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        $original_amount = $invoice->getOriginal('amount');

        $diff = $invoice->amount - $original_amount; 

        $member = Member::find($invoice->member_id);

        if( $member != null )
        {
            $member->balance = $member->balance + $diff; 

            $member->save();
        }

    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
