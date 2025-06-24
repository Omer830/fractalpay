<?php

namespace Modules\Options\Rules;

use Closure;
use Illuminate\Validation\Rule;
use Modules\Options\Models\Options;
use Modules\Options\Enums\Categories;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyOptions implements ValidationRule
{

    public function __construct(
        private $category = null,
        private $name = null,
        private $slug = null
    )
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $query  = Options::query();

        if($this->name) {
            $query->where('name', $this->name);
        }

        if($this->category) {
            $query->where('category', $this->category);
        }

        if($this->slug) {
            $query->where('slug', $this->slug);
        }

        //Check for value in id column
        if(is_numeric($value)) {
            $query->where('id', $value);
        }

        if(!$query->exists()) {
            if ($this->category === Categories::INSURANCE_NAMES) {
                $fail('Invalid company name, please select a valid insurance company from the list.');
            } elseif($this->category === Categories::TERMS_PERIODS) {
                $fail('Invalid term  selected, please select a valid term from the list.');
            }
        }

    }
}
