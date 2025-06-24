<?php

namespace Modules\Wallet\Enums;

use App\Helpers\EnumHelper;

enum UserCommitmentType: string
{

    use EnumHelper;

    case INCOMING = "incoming";
    case OUTGOING = "outgoing";


}