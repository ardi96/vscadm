<?php

namespace App\Services;

use App\Models\User;

class ReactivationService
{

    public static function processReactivation($reactivation)
    {
        $member = $reactivation->member;

        $member->status = 'active';
        $member->save();

        // Additional processing can be added here, such as notifying relevant parties


        $invoice = InvoiceService::createInvoice( $member, $reactivation->amount, now(), 'Biaya Reaktivasi','Biaya Reaktivasi');

        if ( $invoice )
        {
            $payment = $invoice->payment()->create([
                'amount' => $invoice->amount,
                'payment_date' => now(),
                'bank' => 'other',
                'file_name' => $reactivation->file_name,
                'notes' => 'Biaya Reaktivasi',
                'status' => 'accepted',
                'user_id' => auth()->user()->id,
                'member_id' => $member->id
            ]);

            if ($payment)
            {
                $invoice->payNow();
            }
        }
    }

    public static function approveReactivation($reactivation, User $approver)
    {
        $reactivation->status = 1; // Approved
        $reactivation->approver_id = $approver->id;
        $reactivation->save();

        // Resign diproses pada tanggal efektif pengunduran diri
        self::processReactivation($reactivation);
    }
}