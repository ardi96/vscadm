<?php


namespace App\Services;

class PeriodDropdownService
{
    public static function getPeriodOptions(int $monthsBack = 12, int $monthsForward = 12): array
    {
        $options = [];
        $currentDate = now()->startOfMonth();
        $startDate = $currentDate->copy()->subMonths($monthsBack);
        $endDate = $currentDate->copy()->addMonths($monthsForward);

        while ($startDate->lessThanOrEqualTo($endDate)) {
            $options[$startDate->format('Y-m-01')] = $startDate->format('M-Y');
            $startDate->addMonth();
        }

        return $options;
    }
}