<?php

namespace Modules\UserKyc\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Attribute;
use Modules\Options\Models\Options;

class ValidPostcode implements ValidationRule
{

    public function __construct(private $country)
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $options = Options::findById($this->country, Categories::COUNTRIES);

        if (!$options) {
            $fail('Invalid country');
            return;
        }

        $pattern = Attribute::findAttribute($this->country, Categories::COUNTRIES, Attributes::POSTAL_PATTERN);

        if (!$pattern) {
            $fail('Unable to verify postcode');
        }

        if(!preg_match("{$pattern}", $value)){
            $fail('Please enter a valid postal code.');
        }


    }
}