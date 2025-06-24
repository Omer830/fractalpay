<?php

namespace Modules\Auth\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckName implements ValidationRule
{


    public function __construct()
    {
       
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = request();

        if (!$request->filled('first_name') && !$request->filled('last_name')) {
            $fail('Name is required. Please provide either first name or last name.');
        }
        
        if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
            $fail('No special characters are allowed in the name.');
        }
        

    }
}
