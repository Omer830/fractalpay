<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Auth\Rules\VerifyOTP;
use Modules\Auth\Rules\CheckEmail;
use Modules\Auth\Rules\CheckPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Auth\Rules\CheckSecondaryEmail;
use Modules\Auth\Rules\CheckAlternativeEmail;

class VerifyOTPRequest extends FormRequest
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
            'email' => [
                'required_without_all:phone_number,secondary_email,alternative_email',
                'email',
                new CheckEmail()
            ],

            'phone_number' => [
                'required_without_all:email,secondary_email,alternative_email',
                'string',
                new CheckPhone()
            ],

            'secondary_email' => [
                'required_without_all:email,phone_number,alternative_email',
                'email',
                new CheckSecondaryEmail()
            ],

            'alternative_email' => [
                'required_without_all:email,phone_number,secondary_email',
                'email',
                new CheckAlternativeEmail()
            ],

            'otp' => new VerifyOTP(data: $this->all()),

            'type' => 'sometimes|in:forgetPassword'
        ];
    }


    protected function prepareForValidation()
    {
        if ($this->email) {
            $this->merge(['email' => strtolower($this->email)]);
        }

        if ($this->secondary_email) {
            $this->merge(['secondary_email' => strtolower($this->secondary_email)]);
        }

        if ($this->alternative_email) {
            $this->merge(['alternative_email' => strtolower($this->alternative_email)]);
        }
    }
}
