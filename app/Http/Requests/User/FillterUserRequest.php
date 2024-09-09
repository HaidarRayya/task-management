<?php

namespace App\Http\Requests\User;

use App\Rules\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class FillterUserRequest extends FormRequest
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
            'role' => ['sometimes', new UserRole],
            'user_name' => ['sometimes', 'nullable', 'string'],
        ];
    }
}