<?php

namespace Modules\Auth\Rules;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Modules\Auth\Models\User;

class VerifyOTP implements ValidationRule
{

    private mixed $data;
    private bool $exists;
    private bool $required;
    private mixed $length;

    public function __construct($data, $exists = true, $required = true, $length = 4)
    {
        $this->data = $data;
        $this->exists = $exists;
        $this->required = $required;
        $this->length = $length;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $query = User::query();
        if($this->required && $value === null) {
            $fail('OTP is required.');
            return;
        }

        if($this->exists) {
            $query = $query->where('otp', $value);
            if(!$query->exists()) {
                $fail('Incorrect OTP.');
            }
        }


        if(ISSET($this->data['email'])) {
            $query = $query->where('email', $this->data['email']);
        }
        else if(ISSET($this->data['phone_number'])) {
            $query = $query->where('phone_number', $this->data['phone_number']);
        }

        if(!$query->exists()) {
            $fail('Incorrect OTP.');
        }

    }
}
