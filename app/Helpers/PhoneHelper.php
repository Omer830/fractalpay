<?php

namespace App\Helpers;

class PhoneHelper {

    public static function formatPhoneNumber($value, $countryCode = "+61")
    {
        // Remove leading zero if present
        if (substr($value, 0, 1) === '0') {
            $value = substr($value, 1);
        }

        // Concatenate with country code
        $phoneNumber =  $countryCode . $value;

        request()->merge(['phone_number' => $phoneNumber]);
        return $phoneNumber;
    }

}