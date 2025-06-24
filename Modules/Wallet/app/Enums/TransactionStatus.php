<?php

namespace Modules\Wallet\Enums;

use App\Helpers\EnumHelper;

enum TransactionStatus: string
{
    use EnumHelper;

    case PENDING = "pending";
    case COMPLETED = "completed";
    case FAILED = "failed";
    case CANCELLED = "cancelled";
    case REFUNDED = "refunded";
    case ACTIVE = "active";

}