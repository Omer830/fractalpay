<?php

namespace Modules\Auth\Repositories;

use Illuminate\Support\Str;
use Modules\Auth\Jobs\SendOTP;
use Modules\Auth\Services\TwilioService;

class HelperRepository
{


    public static function sendNewOTP(array $data)
    {
        $OTP =  self::generateOTP();

        SendOTP::dispatch($data, $OTP);

        return $OTP;
    }

    public static function generateOTP()
    {
        return rand(1000, 9999);
    }

    public static function generateInvitationCode($length = 6)
    {
        return Str::of(Str::random($length))->upper();
    }
}
