<?php

namespace Modules\Auth\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckEmail implements ValidationRule
{

    private bool $unique;

    public function __construct($unique = false)
    {
        $this->unique = $unique;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = strtolower($value);
        
        $exists = DB::table('users')
        ->where(function ($query) use ($value) {
            $query->where('email', $value)
                ->orWhere('secondary_email', $value)
                ->orWhere('alternative_email', $value);
        })
        ->exists();
        
        if (!preg_match('/@.+\..+/', $value)) {
            $fail('The email address must contain a dot in the domain part.');
        }

        if (Auth::check()) {
            $user = Auth::user();
            
            if (!($user->email === $value || 
                    $user->secondary_email === $value || 
                    $user->alternative_email === $value)) {
                $fail('Invalid email address, please check and try again');
                return;
            }
        }

        if($this->unique && $exists) {
            $fail('You already have an account with us, please login or try Forgot password');
            return;
        }

        if (!$this->unique && !$exists) {
            $fail('The :attribute does not exist in our records.');
        }

    }
}
