<?php

namespace App\Http\Requests\User;

use App\Rules\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
            'first_name' => ['required', 'min:3', 'max:10', 'string'],
            'last_name' => ['required', 'min:3',  'max:10', 'string'],
            'email' =>    ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'role' => ['required',  new UserRole]

        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'name' => $this->first_name . ' ' . $this->last_name,
        ]);
    }
    public function attributes()
    {
        return  [
            'first_name' => 'الاسم الاول',
            'last_name' => ' الاسم الاخير',
            'email' => 'الايميل',
            'password' => 'كلمة السر',
            'role' => 'الدور'
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'status' => 'error',
                'message' => "فشل التحقق يرجى التأكد من صحة القيم مدخلة",
                'errors' => $validator->errors()
            ],
            422
        ));
    }

    public function messages()
    {
        return  [
            'required' => 'حقل :attribute هو حقل اجباري ',
            'email' => 'حقل :attribute يجب ان  يكون ايميل  ',
            'min' => 'حقل :attribute يجب ان  يكون على الاقل 3 محرف',
            'max' => 'حقل :attribute يجب ان  يكون على الاكثر 10 محرف',
            'unique' => 'حقل :attribute  مكرر ',
            'string' => 'حقل :attribute  يجب ان يكون نص ',
            'regex' => 'حقل :attribute  يجب ان يكون يحتوي على حرف صغير وحرف كبير ورمز ورقم واحد عالاقل ',
        ];
    }
}
