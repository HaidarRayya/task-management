<?php

namespace App\Http\Requests\Task;

use App\Models\Task;
use App\Rules\EmployeeTaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', new EmployeeTaskStatus($this->route('task'))]
        ];
    }
    public function attributes()
    {
        return  [
            'status' => 'حالة المهمة',
        ];
    }
}
