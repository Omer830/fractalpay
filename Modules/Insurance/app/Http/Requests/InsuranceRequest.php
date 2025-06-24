<?php

namespace Modules\Insurance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Options\Enums\Categories;
use Modules\Options\Rules\VerifyOptions;

class InsuranceRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'card_number' => preg_replace('/\s+/', '', $this->card_number),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'company_name' => ['required', 'max:255', new VerifyOptions(Categories::INSURANCE_NAMES, slug: $this->company_name)],

            'card_number' => ['required', 'digits:16'],

            'policy_number' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9\-]+$/'],

            'terms' => ['required', 'max:255', new VerifyOptions(Categories::TERMS_PERIODS, slug: $this->terms)],

            'amount' => ['required', 'integer', 'min:1'],

        ];
    }
    public function messages()
    {
        return [
            'company_name.required' => 'Select company name, please check and try again',
            'card_number.required' => 'Select card number, please check and try again',
            'card_number.regex' => 'Card number must be between 13 to 19 digits and contain only numbers',
            'policy_number.required' => 'Select policy number, please check and try again',
            'policy_number.regex' => 'Policy number must be in the format 5xx-xxx-xxx',
            'terms.required' => 'Select terms, please check and try again',
            'amount.required' => 'Select amount, please check and try again',
            'amount.integer' =>  'Invalid amount, please check and try again',
            'amount.min' => 'Amount must be a positive number greater than zero',
            
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
