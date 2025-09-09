<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class LeaveService
{

    public static function createLeave(Member $member, string $startDate, string $endDate, float $biaya, int $createdBy) 
    {
        $leave = \App\Models\Leave::create([
            'member_id' => $member->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'biaya' => $biaya,
            'status' => 0, // pending
            'created_by' => $createdBy,
        ]);

        return $leave;
    }

    public static function approveLeave(\App\Models\Leave $leave, int $approvedBy) : bool
    {
        
        $leave->status = 1; // approved
        $leave->approved_by = $approvedBy;
        $leave->save();

        static::createLeaveInvoice($leave);

        return true;
    }

    public static function rejectLeave(\App\Models\Leave $leave, int $rejectedBy) : bool
    {
        $leave->status = 2; // rejected
        $leave->approved_by = $rejectedBy;
        $leave->save();

        return true;
 
    }

    public static function createLeaveInvoice(\App\Models\Leave $leave) : ?Invoice
    {
        if ($leave->biaya <= 0) {
            return null;
        }

        $member = $leave->member;
 
        $description = "Biaya Cuti";
 
        $itemDescription = "Biaya Cuti Periode " . Carbon::parse($leave->start_date)->format('d M Y') . 
        " - " . Carbon::parse($leave->end_date)->format('d M Y');

        $invoice = InvoiceService::createInvoice(
            $member,
            $leave->biaya,
            Carbon::now()->toDateString(),
            $description,
            $itemDescription
        );

        $invoice->payment()->create([
            'payment_date' => Carbon::now()->toDateString(),
            'amount' => $leave->biaya,
            'bank' => 'other',
            'notes' => 'Pembayaran otomatis biaya cuti oleh sistem',
            'file_name' => $leave->file_name,
            'status' => 'accepted',
            'user_id' => $leave->created_by,
            'member_id' => $member->id,
        ]);

        $invoice->payNow();

        return $invoice;
    }
}