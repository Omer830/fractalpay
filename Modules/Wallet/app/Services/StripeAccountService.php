<?php

namespace Modules\Wallet\Services;

use Modules\Wallet\DTO\PaymentMethodDTO;
use Modules\Wallet\Models\WalletUser;

class StripeAccountService
{

    public function create(WalletUser $user)
    {

        $stripeAccount =  $user->createAsStripeAccount('custom', [
            'country'   =>  $user->country_code,
            'capabilities'  =>  [
                'transfers' =>  ['requested' => true],
                'card_payments' => ['requested' => true]
            ],
            'business_type' =>  'individual',
            'business_profile' =>  [
                'mcc'   =>  '6012', //Todo: make sure it's correct talking to stripe.
                'name'  =>  $user->full_name,
                'product_description'   =>  'Digital Wallet',
                'support_email' =>  $user->email,
                'url'   =>  'https://ezysoft.solutions' //Todo: this should be user profile link
            ],
            'default_currency'  =>  'AUD',
            'individual'    =>  [
                'address'   =>  [
                    'city'  =>  $user->city,
                    'line1' =>  $user->address,
                    'postal_code'   =>  $user->postal_code,
                    'state' =>  $user->state
                ],
                'dob'   =>  $user->date_of_birth_array,
                'email' =>  $user->email,
                'first_name'    =>  $user->first_name,
                'last_name' =>  $user->last_name,
                'phone' =>  $user->phone_number,
            ],
            'tos_acceptance'    =>  [
                'date'  =>  NOW()->unix(),
                'ip'    =>  '180.150.81.55'
            ]
        ]);

    }

    public function createExpernalAccount(PaymentMethodDTO $paymentMethod, $stripeAccount)
    {
        $stripeAccount->external_accounts->create([
            'external_account' => $paymentMethod->accountData(),
        ]);

    }

    public function getAccountDetails(WalletUser $user)
    {
        return $user->asStripeAccount();
    }

}