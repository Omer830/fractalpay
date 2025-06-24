<?php

namespace Modules\Wallet\Enums;

use App\Helpers\EnumHelper;

enum TransactionType: string
{

    use EnumHelper;

    case DEPOSIT = "deposit";
    case PAYMENT = "payment";
    case TRANSFER = "transfer";
    case CONDITIONAL = "conditional";
    case WITHDRAW = "withdraw";
    case UNKNOWN = "unknown";

}