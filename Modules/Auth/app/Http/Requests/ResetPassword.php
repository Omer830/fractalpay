<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Auth\Rules\CheckEmail;
use Modules\Auth\Rules\CheckPhone;
use Modules\Auth\Rules\VerifyOTP;

class ResetPassword extends FormRequest
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

            'email' => [new RequiredIf($this->phone_number === null), 'email', new CheckEmail()],

            'phone_number'  => [new RequiredIf($this->email === null), new CheckPhone()],

            'otp'   =>  new VerifyOTP(data: $this->all()),

            'password' => ['required', 'string', Password::min(8)->mixedCase()],

        ];
    }
}
