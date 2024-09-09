<?php

namespace App\Rules;

use App\Enums\UserRole as EnumsUserRole;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $roles = implode(", ", array_column(EnumsUserRole::cases(), 'value'));

        if (!($value == EnumsUserRole::MANAGER->value ||  $value == EnumsUserRole::EMPLOYEE->value)) {
            $fail($roles . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}