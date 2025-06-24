<?php

namespace Modules\PaymentMethod\Services;

use App\Exceptions\ErrorException;
use Modules\PaymentMethod\DTOs\PaymentMethodDTO;
use Modules\Wallet\Models\WalletUser;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class StripeAccountService
{


    public function createExternalAccount(PaymentMethodDTO $paymentMethod, $stripeAccount)
    {


        if($paymentMethod->accountType() == PaymentMethodDTO::CREDIT_CARD) {

            // Attach the payment method to the connected account
            $stripeAccountId = $stripeAccount->id; // Get the connected account ID

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentMethod = PaymentMethod::retrieve($paymentMethod->getMethodId());

            // Attach the PaymentMethod to the connected account
            $paymentMethod->attach([
                'customer' => $stripeAccountId, // The Stripe connected account ID
            ]);

            return $paymentMethod;


        }
        else if($paymentMethod->accountType() == PaymentMethodDTO::BANK_ACCOUNT) {
            $stripeAccount->external_accounts->create([
                'external_account' => $paymentMethod->accountData(),
            ]);
        }

        throw new ErrorException('INVALID_PAYMENT_METHOD_TYPE');

    }

    public function getAccountDetails(WalletUser $user)
    {
        return $user->asStripeAccount();
    }

}