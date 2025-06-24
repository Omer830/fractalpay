<?php

namespace Modules\ExpenseTracker\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionFrequency;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Rules\CheckConnection;

class PayBillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'type'      =>  ['required', Rule::enum(TransactionType::class)],

            'currency'  =>  ['required', Rule::enum(Currency::class)],

            'amount'    =>  ['required', 'numeric', 'min:10', 'max:1000000000'],

            'frequency' =>  ['required', Rule::enum(TransactionFrequency::class)],

            'start_date'    =>  ['required', 'date', 'date_format:Y-m-d'],

            'end_date'      =>  ['nullable', 'date', 'date_format:Y-m-d'],

            'bill_id'      =>  ['required', 'array'],

            'bill_id.*'    =>  ['required', 'integer', Rule::exists('bills', 'id')],

            'payment_method_id' =>  ['required', 'integer', 'exists:payment_methods,id'],

            'description'   =>  ['nullable', 'string'],

            'is_wallet' => ['nullable', 'boolean', Rule::in([true, false])],

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'frequency'      =>  TransactionFrequency::ONE_OFF->value,
            'start_date'    =>  NOW()->format('Y-m-d'),
        ]);
    }

    public function messages()
    {
        return [
            'receiver_id.exists'    =>  'Invalid user selected',
            'receiver_id.unique'    =>  'There is already a payment schedule exists for this user',
            'receiver_id.prohibited'    =>  'Cannot pay one-off payment to yourself',
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
