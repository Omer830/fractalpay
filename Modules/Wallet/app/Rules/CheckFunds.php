<?php

namespace Modules\Wallet\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Wallet\Models\Wallet;

class CheckFunds implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $balance = Wallet::availableBalance();

        if($value > $balance) {
            $fail('insufficient funds');
        }

    }
}
