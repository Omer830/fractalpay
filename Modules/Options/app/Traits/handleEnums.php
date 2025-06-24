<?php

namespace Modules\Options\Traits;

use Illuminate\Support\Enumerable;

trait handleEnums
{

    public static function cleanValue($var)
    {
        if(ISSET($var->value)) {
            return $var->value;
        }

        return $var;

    }

}