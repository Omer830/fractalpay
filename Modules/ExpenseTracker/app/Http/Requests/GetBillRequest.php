<?php

namespace Modules\ExpenseTracker\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Options\Rules\VerifyOptions;

class GetBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

      /**
     * Prepare the data for validation.
     * This ensures the `id` parameter is included in the validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'), 
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'id'   =>  ['required', 'integer', Rule::exists('bills', 'id')],
        ];
    }

}
