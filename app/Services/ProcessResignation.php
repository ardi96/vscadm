<?php

namespace App\Services;

use App\Models\Resignation;

class ProcessResignation
{
    // --- IGNORE ---

    public function __invoke()
    {
        $resignations = Resignation::where('status', 1)
            ->whereDate('resignation_date', '<=', now())
            ->get();

        foreach ($resignations as $resignation) {
            $member = $resignation->member;  
            $member->status = 'resigned';
            $member->save();            
        }
    }
}