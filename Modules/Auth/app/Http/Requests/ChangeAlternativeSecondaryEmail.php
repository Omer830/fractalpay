<?php

namespace Modules\Auth\Http\Requests;

use Modules\Auth\Rules\CheckEmail;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Rules\CheckAlternativeEmail;
use Modules\Auth\Rules\CheckSecondaryEmail;

class ChangeAlternativeSecondaryEmail extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'secondary_email' => [
                'nullable',
                'email',
                'required_without:alternative_email',
                'different:alternative_email',
                new CheckSecondaryEmail(true),
            ],
            'alternative_email' => [
                'nullable',
                'email',
                'required_without:secondary_email',
                'different:secondary_email',
                new CheckAlternativeEmail(true),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'secondary_email.required_without' => 'Either secondary or alternative email is required.',
            'alternative_email.required_without' => 'Either alternative or secondary email is required.',
            'secondary_email.different' => 'Secondary and alternative emails must be different.',
            'alternative_email.different' => 'Alternative and secondary emails must be different.',
        ];
    }
}
