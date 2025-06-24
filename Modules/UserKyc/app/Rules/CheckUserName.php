<?php

namespace Modules\UserKyc\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckUserName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value contains any spaces
        if (strpos($value, ' ') !== false) {
            $fail('The username cannot contain spaces.');
        }
    }
}