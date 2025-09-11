<?php

namespace App\Services;

use App\Models\User;

class ResignationService
{

    public static function processResignation($resignation)
    {
        $member = $resignation->member;

        // Update member status to 'resigned'
        $member->status = 'resigned';
        $member->save();

        // Additional processing can be added here, such as notifying relevant parties
    }

    public static function approveResignation($resignation, User $approver)
    {
        $resignation->status = 1; // Approved
        $resignation->approver_id = $approver->id;
        $resignation->save();

        // Resign diproses pada tanggal efektif pengunduran diri
        // self::processResignation($resignation);
    }
}