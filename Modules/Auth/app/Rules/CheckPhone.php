<?php

namespace Modules\Auth\Rules;

use App\Helpers\PhoneHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Attribute;
use Modules\Options\Models\Options;

class CheckPhone implements ValidationRule
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
    
        $exists = DB::table('users')->where('phone_number', $value)->exists();

        if($this->unique && $exists) {
            $fail('You already have an account with us, please login or try Forgot password');
            return;
        }

        if (!$this->unique && !$exists) {
            $fail('The :attribute does not exist in our records.');
        }
        request()->merge(['phone_number' => $value]);

    }
}
