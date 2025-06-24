<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ExcludeIf;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Rules\CheckFunds;

class CreateTransaction extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'amount' => ['required', 'numeric', new CheckFunds()],

            'currency' => 'required',

            'receiver_id'   =>  [
                new RequiredIf($this->type === TransactionType::TRANSFER->value),
                new ExcludeIf($this->type !== TransactionType::TRANSFER->value),
                'required', 'exists:users,id'
            ],

            'type'  =>  Rule::enum(TransactionType::class)

        ];
    }

    public function prepareForValidation()
    {
        if(!$this->currency) {
            $this->merge([
                'currency' => env('DEFAULT_CURRENCY'),
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
