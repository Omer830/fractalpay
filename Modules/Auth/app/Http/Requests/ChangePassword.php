<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'new_password' => [
                'required',
                'string',
                Password::min(8)->mixedCase(), 
                'different:old_password',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'Your current password is required.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.different' => 'The new password must be different from the old password.',
        ];
    }
}
