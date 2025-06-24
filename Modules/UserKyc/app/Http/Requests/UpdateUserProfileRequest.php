<?php

namespace Modules\UserKyc\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Options\Rules\VerifyOptions;
use Modules\UserKyc\app\Rules\CheckUserName;
use Modules\UserKyc\Enums\Gender;
use Modules\UserKyc\Rules\ValidPostcode;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'user_name' =>  ['required', 'string', 'max:255', Rule::unique('users', 'user_name')
                ->ignore(Auth::id()), new CheckUserName],

            'address'   =>  ['required', 'string', 'max:255'],

            'state'     =>  ['required', 'string', 'max:255'],

            'city'      =>  ['required', 'string', 'max:255'],

            'country_id'   =>  ['required', 'numeric', new VerifyOptions(category: 'countries')],

            'gender'        =>  ['required', 'string', 'max:255', Rule::enum(Gender::class)],

            'date_of_birth'   =>  ['required', 'date', 'date_format:d-m-Y'],

            'postal_code'   =>  ['required', 'string', new ValidPostcode($this->country_id)],

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
