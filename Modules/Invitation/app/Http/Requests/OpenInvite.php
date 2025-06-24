<?php

namespace Modules\Invitation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Modules\Auth\Enums\CommonKeys;
use Modules\Invitation\Enums\InvitationStatus;

class OpenInvite extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            CommonKeys::REFEREE_CODE->value  =>  [
                'string',
                Rule::exists('invitations', 'invitation_code')
                    ->whereNotIn('invitation_status', [InvitationStatus::ACCEPTED, InvitationStatus::REJECTED]),
                new RequiredIf('invitation_id' == null)
            ],

            'invitation_id' => [
                'integer',
                Rule::exists('invitations', 'id')
                    ->where(function ($query) {
                        $query->where('email', Auth::user()?->email)
                            ->orWhere('phone_number', Auth::user()?->phone_number);
                    })
                    ->whereNotIn('invitation_status', [InvitationStatus::ACCEPTED, InvitationStatus::REJECTED])
                ,
                new RequiredIf('invitation_id' == null)
            ],

            CommonKeys::UNIQUE_CODE->value   =>  ['sometimes', 'string']
        ];
    }

    protected function prepareForValidation()
    {
        if($this->invitation) {
            $this->merge([
               'invitation_id'  =>  $this->invitation->id
            ]);
        }
    }

    public function messages()
    {
        return [
            CommonKeys::REFEREE_CODE->value .'exists'   =>  'Invalid referee Code',
            'invitation_id.exists' =>  'Invalid Invitation',
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
