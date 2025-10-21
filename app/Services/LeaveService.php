<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use App\Models\GlobalParameter;
use Illuminate\Support\Facades\DB;

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

    public static function checkPaidMembershipFee(Leave $leave)
    {
        // check if customer has paid membership fee for the leave month
        // we check from invoice table instead of payment table 
        // if exist, we update the invoice status to 'void'

        $paidMembership =  \App\Models\Invoice::where('member_id', $leave->member_id)
            ->where('type', 'membership')
            ->whereRaw('STR_TO_DATE(CONCAT(invoice_period_year, \'-\', invoice_period_month, \'-01\'), \'%Y-%m-%d\') = ?', [$leave->start_date])
            ->where('status', 'paid')
            ->first();

        if ($paidMembership) {
                $paidMembership->status = 'void';
                $paidMembership->save();
        }

    }

    public static function checkUnpaidMembershipFee(Leave $leave)
    {
        // check if customer invoice membership fee for the leave month
        // we check from invoice table instead of payment table 
        // if exist, we update the invoice status to 'void' and update the balance

        $unpaidMembership =  \App\Models\Invoice::where('member_id', $leave->member_id)
            ->where('type', 'membership')
            ->whereRaw('STR_TO_DATE(CONCAT(invoice_period_year, \'-\', invoice_period_month, \'-01\'), \'%Y-%m-%d\') = ?', [$leave->start_date])
            ->where('status', 'unpaid')
            ->first();

        if ($unpaidMembership) {
            
            $unpaidMembership->status = 'void';
            $unpaidMembership->save();
            
            // update member balance
            $amount = $unpaidMembership->amount;
            $member = $leave->member;
            $member->balance -= $amount;
            $member->save();
        }
    }

    public static function approveLeave(\App\Models\Leave $leave, int $approvedBy) : bool
    {
        try
        {
            DB::beginTransaction();

            // Update leave status
            $leave->status = 1; // approved
            $leave->approved_by = $approvedBy;
            $leave->save();

            // Create invoice for leave
            static::createLeaveInvoice($leave);

            // Check if membership fee for the leave month is paid
            static::checkPaidMembershipFee($leave);

            // Check if membership fee for the leave month is unpaid
            static::checkUnpaidMembershipFee($leave);
            
            DB::commit();
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            throw $e;
        }

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

    public static function getBiayaCuti($start_date, $end_date)
    {
        if ($start_date && $end_date) {
     
            $start = \Carbon\Carbon::parse($start_date);

            $end = \Carbon\Carbon::parse($end_date);

            $months = $start->diffInMonths($end) + 1;

            $biaya_per_bulan = GlobalParameter::where('parameter_key', 'BIAYA_CUTI_PER_BULAN')->first()->decimal_value;
     
            $total_biaya = $months * $biaya_per_bulan;

            return $total_biaya;
        }

        return 0;
    }
}