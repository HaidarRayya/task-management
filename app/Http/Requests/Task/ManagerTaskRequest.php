<?php

namespace App\Http\Requests\Task;

use App\Rules\ManagerTask;
use Illuminate\Foundation\Http\FormRequest;

class ManagerTaskRequest extends FormRequest
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
            'manager_id' => ['required', new ManagerTask, 'integer', 'gt:0']
        ];
    }

    public function attributes()
    {
        return  [
            'manager_id' => 'رقم المدير',
        ];
    }
}
