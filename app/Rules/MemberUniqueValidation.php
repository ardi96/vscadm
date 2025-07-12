<?php

namespace App\Rules;

use Closure;
use App\Models\Member;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class MemberUniqueValidation implements ValidationRule, DataAwareRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $name = strtoupper($this->data['data']['name'] ?? '');

        Member::whereRaw('upper(name) = ?', [$name])
            ->where('date_of_birth', $this->data['data']['date_of_birth'])
            ->when($this->data['data']['id'] ?? null, function ($query) {
                return $query->where('id', '!=', $this->data['data']['id']);
            })
            ->exists()
            ? $fail('Nama Lengkap dan Tanggal Lahir sudah terdaftar.')
            : null;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
