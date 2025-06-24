<?php

namespace Modules\ExpenseTracker\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Options\Rules\VerifyOptions;

class AddVisitRequest extends FormRequest
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

            'user_id'           =>  ['required'],

            'visit_reason'      =>  ['required', 'string', 'max:20'],

            'visit_details'     =>  ['nullable'],

            'name'              =>  ['required', 'string'],

            'provider_number'   =>  ['required', new VerifyOptions('provider_number', $this->provider_number)],

            'organisation'      =>  ['required', new VerifyOptions('organization', $this->organisation)],

            'visit_type'        =>  ['required', new VerifyOptions('visit_type', $this->visit_type)],

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
