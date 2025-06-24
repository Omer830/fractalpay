<?php

namespace Modules\ExpenseTracker\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Options\Rules\VerifyOptions;

class AddBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [

            'visit_id'          => ['required', 'integer', Rule::exists('visits', 'id')],

            'category'          => ['required', new VerifyOptions('bills', $this->category)],

            'amount'            => ['required', 'numeric', 'min:0'],

            'description'       => ['nullable', 'string'],

            'invoice_number'    => ['required', 'string', 'max:255'],

            'due_date'          => ['required', 'date'],

            'already_paid'      => ['required', 'boolean'],

            'contributorsIds'   =>  ['nullable', 'array'],

            'contributorsIds.*' =>  ['exists:users,id'],

        ];
    }

}
