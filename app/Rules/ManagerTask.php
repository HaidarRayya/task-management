<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ManagerTask implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $managers = User::managers()->select('user_id')->get();
        $managers_id = [];
        foreach ($managers as $i) {
            array_push($managers_id, $i->user_id);
        }
        if (!in_array($value, $managers_id)) {
            $fail(" حقل :attribute خاطئ , تحقق من رقم المدير");
        }
    }
}
