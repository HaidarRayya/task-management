<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $employees = User::employees()->select('user_id')->get();
        $employees_id = [];
        foreach ($employees as $i) {
            array_push($employees_id, $i->user_id);
        }
        if (!in_array($value, $employees_id)) {
            $fail(" حقل :attribute خاطئ , تحقق من رقم الموظف");
        }
    }
}
