<?php

namespace Modules\Wallet\Enums;

use App\Helpers\EnumHelper;

enum Currency: string
{

    use enumHelper;

    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
    case JPY = 'JPY';
    case AUD = 'AUD';

}

