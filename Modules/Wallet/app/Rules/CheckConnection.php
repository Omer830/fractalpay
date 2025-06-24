<?php

namespace Modules\Wallet\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckConnection implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (Auth::id() === $value) {
            $fail('You cannot make a payment to yourself. Please select a different recipient or schedule the payment for someone else.');
            return;
        }


        $query = DB::table('invitations')
            ->where(function ($query) use ($value) {
                $query->where(function ($q) use ($value) {
                    // Authenticated user sent an invitation
                    $q->where('user_id', Auth::id())
                        ->where('invited_user', $value);
                })
                ->orWhere(function ($q) use ($value) {
                    // Authenticated user received an invitation
                    $q->where('user_id', $value)
                        ->where('invited_user', Auth::id());
                });
            });


        if(!$query->exists()) {
            $fail('No connection found. Please invite the user before proceeding.');
            return;
        }

        $invitation = $query->first();

        if($invitation && $invitation->invitation_status != 'accepted') {
            $fail('The user has not accepted the invitation yet.');
        }


    }

}
