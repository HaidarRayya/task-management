<?php

namespace App\Rules;

use App\Services\UserService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OneTaskEmployee implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userService = new UserService();
        $users = $userService->availableEmployees(['user_name' => null]);
        $x = [];
        foreach ($users as $user) {
            array_push($x, $user->user_id);
        }
        if (!in_array($value, $x)) {
            $fail("هذا الموظف لديه مهمة موكله له");
        }
    }
}
