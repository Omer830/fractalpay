<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Models\ExchangeRate;

class ExchangeRateHelper
{
    const EXPIRY = 'expiry';
    const EXCHANGE_RATE = 'exchange_rate';
    const AMOUNT = 'amount';

    public static function getExchangeRate($currency)
    {

        if($currency === Currency::EUR->value) {
            return 1;
        }

        $expiry = Cache::get(self::EXPIRY);

        if(!$expiry) {
            self::fetchExchangeRates();
        }

        return \Modules\Wallet\Models\ExchangeRate::findByName($currency);

    }

    public static function fetchExchangeRates()
    {
        $response = Http::get(
            'https://api.exchangeratesapi.io/v1/latest?access_key=' . env('EXCHANGE_RATE_API')
        );

        Cache::put(self::EXPIRY, time(), 30);

        if($response->ok()) {

            $data = $response->object();

            foreach($data->rates as $currency => $rate)
            {
                \Modules\Wallet\Models\ExchangeRate::updateOrCreate(
                    [
                        'base'  =>  $data->base ?? Currency::EUR->value,
                        'currency'  =>  $currency
                    ],
                    [
                        'rate'  =>  $rate,
                        'timestamp' =>  $data->timestamp
                    ]
                );
            }

        }
    }

    public static function amountInBaseCurrency($amount, $exchangeRate)
    {
        return ($amount/$exchangeRate);
    }

    public static function getAmount($currency, $amount, $transactionExchangeRate)
    {
        if(!$currency) {
            return[];
        }
        $amountInBaseCurrency = self::amountInBaseCurrency($amount, $transactionExchangeRate);
        $currencyExchangeRate = ExchangeRate::findByName($currency);
        return [$currencyExchangeRate, ($currencyExchangeRate*$amountInBaseCurrency)];
    }


}