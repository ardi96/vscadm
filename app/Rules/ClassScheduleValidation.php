<?php

namespace App\Rules;

use App\Models\ClassPackage;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Mockery\Generator\StringManipulation\Pass\ClassPass;

class ClassScheduleValidation implements DataAwareRule,  ValidationRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     *
     * 19/2/2025 : as the session_per_week is now change to session per month actually, 
     * so divide the package session per month by 4 to get the session per week
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       
        if ($this->data['data']['class_package_id'] === null )
        {
            return;
        }

        $package = ClassPackage::find( $this->data['data']['class_package_id']);

        $session_per_week = $package->session_per_week / 4; 
        
        if ( $session_per_week > 0 && $package->type === 'regular')
        {
            $schedules = $this->data['data']['schedules'];

            if ( count($schedules) > $session_per_week )
            {
                $fail('Jumlah session per minggu tidak sesuai dengan paket yang dipilih');
            }
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
