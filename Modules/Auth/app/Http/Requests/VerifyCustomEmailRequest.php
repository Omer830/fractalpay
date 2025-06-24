<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Auth\Rules\CheckAlternativeEmail;
use Modules\Auth\Rules\CheckSecondaryEmail;
use Modules\Auth\Rules\VerifyOTP;

class VerifyCustomEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'secondary_email' => [
                new RequiredIf(fn() => !$this->alternative_email),
                'email', 
                new CheckSecondaryEmail() 
            ],
            'alternative_email' => [
                new RequiredIf(fn() => !$this->secondary_email),
                'email', 
                new CheckAlternativeEmail()
            ],
            'otp' => [
                'required',
            ]
        ];
    }
}