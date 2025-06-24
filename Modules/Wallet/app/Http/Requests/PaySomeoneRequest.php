<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionFrequency;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Rules\CheckConnection;

class PaySomeoneRequest extends FormRequest
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

            'receiver_id'   =>  ['required', 'integer',

                new CheckConnection(),

                Rule::unique('schedules', 'receivable_id')
                    ->where(function ($query) {
                        $query->where('user_id', Auth::id());
                    }),
            ],

            'payment_method_id' =>  ['required', 'integer', 'exists:payment_methods,id'],

            'description'   =>  ['nullable', 'string']

        ];
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
