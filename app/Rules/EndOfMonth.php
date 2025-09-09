<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EndOfMonth implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $date = Carbon::parse($value);

            if (!$date->isLastOfMonth()) {

                $fail('Field :attribute harus tanggal akhir bulan.');
            
            }

        } catch (\Exception $e) {
            $fail('bukan tanggal yang valid.');
        }
    }
}