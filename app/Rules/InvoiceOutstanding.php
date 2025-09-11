<?php

namespace App\Rules;

use Closure;
use App\Models\Member;
use Illuminate\Contracts\Validation\ValidationRule;

class InvoiceOutstanding implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $member = Member::find($value);
        if ( $member && $member->invoices()->where('status', 'unpaid')->exists() ) {
            $fail('Masih ada tagihan yang belum dibayar. Mohon diselesaikan terlebih dahulu.');
        }
    }
}
