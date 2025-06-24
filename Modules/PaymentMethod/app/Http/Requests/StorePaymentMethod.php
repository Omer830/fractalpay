<?php

namespace Modules\PaymentMethod\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\PaymentMethod\Enums\PaymentMethodTypes;

class StorePaymentMethod extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules =  [
            'type'  => Rule::enum(PaymentMethodTypes::class),
        ];

        if($this->type === PaymentMethodTypes::CREDIT_CARD->value) {

            $rules += [

                'account_name'  => ['required', 'string'],

                'card_number'  => ['required', 'string', 'min:6', 'regex:/^[a-zA-Z0-9\s\-]+$/'],

                'expiry'  => ['required', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],

                'cvv'  => ['required', 'numeric', 'min:3'],

            ];

        }

        if($this->type === PaymentMethodTypes::BANK_ACCOUNT->value) {

            $rules += [

                'bank_name'  => ['required', 'string', 'min:2', 'max:30'],

                'account_number' => ['required', 'string', 'min:6', 'regex:/^[a-zA-Z0-9\s\-]+$/'],

                'account_name'  => ['required', 'string'],

                'bsb'   =>  ['required', 'numeric', 'min:6'],

            ];

        }


        return $rules;

    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
