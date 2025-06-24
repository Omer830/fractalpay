<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\PhoneHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Auth\Rules\CheckEmail;
use Modules\Auth\Rules\CheckPhone;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Attribute;
use Modules\Options\Rules\VerifyOptions;

class InsuranceRequest extends FormRequest
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

            'comany_name' => ['required', 'string', 'max:255'],

            'card_number' => ['required', 'string', 'max:255'],

            'policy_number' => ['required', 'string','max:255'],

            'terms' => ['required', 'string','max:255'],

            'amount'   => ['required', 'numeric'],

            'user_id'  =>  ['required', 'numeric'],

        ];
    }

    protected function prepareForValidation()
    {
        

    }

    public function messages()
    {
        return [
            'comany_name.required' => 'Invalid comany_name, please check and try again',
            'card_number.required' => 'Invalid card_number, please check and try again',
            'policy_number.required' => 'Invalid policy_number, please check and try again',
            'terms.required' => 'Invalid terms, please check and try again',
            'amount.numeric'    =>  'Invalid amount, please check and try again',
            'user_id.numeric'    =>  'Invalid user_id, please check and try again'
        ];
    }

}
