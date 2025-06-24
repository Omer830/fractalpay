<?php

namespace Modules\Invitation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Invitation\Enums\InvitationStatus;

class SendInvitation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email'     =>  ['required', 'array'],
            'email.*'   =>  ['string', 'email', 'max:255',
                Rule::unique('invitations', 'email')
                    ->where(function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->whereIn('invitation_status', [InvitationStatus::ACCEPTED]),
            
                ],
                'bill_id'   => ['sometimes', 'exists:bills,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $emails = $this->email; //array of emails

        if(is_array($emails)) {
            $this->merge(['email' => array_filter($emails, function($email) {
                return $email !== Auth::user()?->email;
                })
            ]);
        }

    }

    public function messages()
    {
        return [
            'email.*.unique'    =>  'Already linked with user',
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
