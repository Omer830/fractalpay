<?php

namespace Modules\ExpenseTracker\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateVisitRequest extends FormRequest
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
            'user_id'           =>  'required',

            'visit_reason'      =>  ['sometimes', 'string', 'max:20'],

            'visit_details'     =>  ['nullable'],

            'name'              =>  ['sometimes', 'string'],

            'provider_number'   =>  ['sometimes', 'exists:doctors,id'],

            'organisation'      =>  ['sometimes', 'exists:organizations,id'],

            'visit_type'        =>  ['sometimes', 'exists:visit_types,id'],

            'contributorsIds'   =>  ['nullable', 'array'],

            'contributorsIds.*' =>  ['exists:users,id'],
        ];
    }

    protected function prepareForValidation()
    {
        // Automatically set the user_id from the authenticated user
        $this->merge([
            'user_id' => Auth::id(),
        ]);
    }

}
