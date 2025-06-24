<?php

namespace Modules\PaymentMethod\Enums;

use App\Helpers\EnumHelper;

enum PaymentMethodTypes: string
{
    use EnumHelper;

    case CREDIT_CARD = 'credit_card';
    case BANK_ACCOUNT = 'bank_account';
    case BPAY = 'bpay';
    case BANK_TRANSFER = 'bank_transfer';
    case PAY_PAL = 'paypal';

}