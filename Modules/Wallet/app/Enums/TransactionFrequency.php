<?php

namespace Modules\Wallet\Enums;

use App\Helpers\EnumHelper;

enum TransactionFrequency: string
{

    use EnumHelper;

    case ONE_OFF = 'one-off';
    case WEEKLY = 'weekly';
    case FORTNIGHTLY = 'fortnightly';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';


}