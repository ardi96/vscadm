<?php

namespace App\Rules;

use Closure;
use App\Models\Member;
use Illuminate\Contracts\Validation\ValidationRule;

class ResignationPending implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $member = Member::find($value);
        if ( $member && $member->resignations()->where('status', 0)->exists() ) {
            $fail('Pengunduran diri untuk anggota ini sedang dalam proses.');
        }
    }
}
