<?php

namespace App\Helpers;

class ValueHelpers
{

    public static function getValues(array|string $values)
    {

        if(is_string($values)) {
            return [strtolower($values)];
        }

        return array_map(function ($value) {
            return self::getEnumValue($value) ?? $value; // Return as-is if neither string nor enum
        }, $values);
    }

    public static function getEnumValue($item)
    {
        if(is_object($item) && ISSET($item->value)) {
            return strtolower($item->value);
        }
        elseif (is_string($item)) {
            // Handle string case
            return strtolower($item);
        }
        return false;
    }

}