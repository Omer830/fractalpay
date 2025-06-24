<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionFrequency;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Rules\CheckConnection;

class AddDepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'type'  =>  ['required', Rule::enum(TransactionType::class)],

            'currency'  =>  ['required', Rule::enum(Currency::class)],

            'amount'    =>  ['required', 'numeric', 'min:10', 'max:1000000000'],

            'frequency' =>  ['required', Rule::enum(TransactionFrequency::class)],

            'start_date'    =>  ['required', 'date', 'date_format:Y-m-d'],

            'end_date'      =>  ['nullable', 'date', 'date_format:Y-m-d'],

            'payment_method_id' =>  ['required', 'integer', 'exists:payment_methods,id'],

            'description'   =>  ['nullable', 'string']

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
