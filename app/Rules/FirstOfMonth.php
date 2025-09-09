<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FirstOfMonth implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {

            $date = Carbon::parse($value.' 00:00:00')->subDay();

            if (!$date->isLastOfMonth()) {
                $fail('harus tanggal awal bulan.');
            }

        } catch (\Exception $e) {
            $fail('bukan tanggal yang valid.');
        }
    }
}