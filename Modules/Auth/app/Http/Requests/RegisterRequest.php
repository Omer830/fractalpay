<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\PhoneHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Auth\Rules\CheckEmail;
use Modules\Auth\Rules\CheckName;
use Modules\Auth\Rules\CheckPhone;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Attribute;
use Modules\Options\Rules\VerifyOptions;

class RegisterRequest extends FormRequest
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

            'first_name' => ['required', 'string', 'max:255', new CheckName],

            'last_name' => ['required', 'string', 'max:255', new CheckName],

            'email' => ['required', 'string', 'email', 'max:255', new CheckEmail(true)],

            'password' => ['required', 'string', Password::min(8)->mixedCase()],
            
            // Commented Fields for new design 

            // 'country_id'   => ['required', 'numeric', new VerifyOptions(category: 'countries')],

            // 'phone_number'  =>  ['required', 'numeric', new CheckPhone(true)],

            'referee_code'  =>  [
                'nullable',
                Rule::exists('invitations', 'invitation_code')
            ]

        ];
    }

    protected function prepareForValidation()
    {
        $countryId = $this->input('country_id');
        $phoneNumber = $this->input('phone_number');

        if ($countryId) {
            $countryCode = Attribute::findAttribute($countryId, Categories::COUNTRIES, Attributes::COUNTRY_DIAL_CODE);
            if ($countryCode && $phoneNumber) {
                PhoneHelper::formatPhoneNumber($this->input('phone_number'), $countryCode);
            }
        }
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid string.',
            'first_name.max' => 'First name must not exceed 255 characters.',
            
            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid string.',
            'last_name.max' => 'Last name must not exceed 255 characters.',
            
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid string.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email is already registered.',
            
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
        ];
    }

}