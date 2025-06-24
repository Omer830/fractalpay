<?php

namespace Modules\PaymentMethod\Factories;

use Modules\PaymentMethod\Enums\PaymentMethodTypes;
use Modules\PaymentMethod\Repositories\BankPaymentRepositories;
use Modules\PaymentMethod\Repositories\CardPaymentRepositories;
use Twilio\TwiML\Voice\Pay;

class PaymentRepositoryFactory
{
    public static function getRepository(PaymentMethodTypes $paymentMethod)
    {
        switch ($paymentMethod) {
            case PaymentMethodTypes::CREDIT_CARD:
                return new CardPaymentRepositories();
            case PaymentMethodTypes::BANK_ACCOUNT:
                return new BankPaymentRepositories();
            default:
                throw new \Exception("Payment method not supported");
        }
    }
}
