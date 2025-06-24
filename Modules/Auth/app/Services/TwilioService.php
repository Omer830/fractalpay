<?php

namespace Modules\Auth\Services;

use Twilio\Rest\Client;

class TwilioService {

    private $twilio;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->twilio = new Client($sid, $token);
    }

    public function sendOTP()
    {

//        $this->twilio->messages->create(
//        // The number you'd like to send the message to
//            '+61456639389',
//            [
//                // A Twilio phone number you purchased at https://console.twilio.com
//                'from' => '+61238138517',
//                // The body of the text message you'd like to send
//                'body' => "Hey Jenny! Good luck on the bar exam!"
//            ]
//        );
//        $verification = $this->twilio->verify->v2->services("VA6286c78309b5b97e4fcb14fd9d4e3e8f")
//            ->verifications
//            ->create("+610456639389", "sms");
//
//        print($verification->sid);
    }

}
