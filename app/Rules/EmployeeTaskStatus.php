<?php

namespace App\Rules;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeTaskStatus implements ValidationRule
{
    protected $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value == 'start' ||  $value == 'end')) {
            $fail("start او end يجب ان يكون احد القيم .");
        } else {
            if ($value == 'start') {
                if ($this->task->status == 3) {
                    $fail("لقد بدأت هذه المهمة مسبقا");
                } else if ($this->task->status == 4) {
                    $fail("لا يمكنك بدء هذه المهمة لقد انتهت");
                }
            } else {
                if ($this->task->status == 2) {
                    $fail("لم تبدأ هذه المهمة بعد");
                } else if ($this->task->status == 4) {
                    $fail("لا يمكنك انهاء هذه المهمة لقد انتهت مسبقا");
                }
            }
        }
    }
}
