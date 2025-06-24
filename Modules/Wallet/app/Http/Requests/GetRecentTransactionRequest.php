<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Wallet\Enums\UserCommitmentType;

class GetRecentTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => [   'required','integer', Rule::exists('users', 'id'), ], 
            
            'contributer_type' => ['required', Rule::enum(UserCommitmentType::class)],
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
