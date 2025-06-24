<?php

namespace Modules\Auth\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Auth\Rules\CheckEmail;
use Modules\Auth\Rules\CheckPhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendOTPRequest extends FormRequest
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

            'email' =>  [new RequiredIf($this->phone_number === null), 'email', new CheckEmail()],

            'phone_number'  =>  [new RequiredIf($this->email === null), 'numeric', new CheckPhone()],

        ];
    }

    protected function prepareForValidation()
    {

        if(Auth::check()) {

            if(!$this->email && !$this->phone_number)
            {
                $this->merge([
                    'email' =>  Auth::user()->email
                ]);
            }


        }

    }
}
