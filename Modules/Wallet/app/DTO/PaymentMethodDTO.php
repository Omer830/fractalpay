<?php

namespace Modules\Wallet\DTO;

class PaymentMethodDTO
{

    private $values;
    const BANK_ACCOUNT = 'bank_account';
    const CREDIT_CARD = 'credit_card';

    public function __construct(array $values)
    {
        $this->values = (object)$values;
    }

    public function accountType()
    {
        return $this->values->account_type;
    }

    public function accountData()
    {
        if($this->accountType() === self::BANK_ACCOUNT)
        {
            return [
                'object' => $this->accountType(),
                'country' => $this->country(),
                'currency' => $this->currency(),
                'account_holder_name' => $this->accountName(),
                'account_holder_type' => $this->accountHolderType(),
                'routing_number' => $this->routingNumber(),
                'account_number' => $this->accountNumber()
            ];
        }
        if($this->accountType() === self::CREDIT_CARD)
        {
            return [
                    "object" => "card",
                    "address_city" => null,
                    "address_country" => null,
                    "address_line1" => null,
                    "address_line1_check" => null,
                    "address_line2" => null,
                    "address_state" => null,
                    "address_zip"   => null,
                    "address_zip_check" => null,
                    "brand" => "Visa",
                    "country" => $this->country(),
                    "cvc_check" => null,
                    "dynamic_last4" => null,
                    "exp_month" => 4,
                    "exp_year" => 2024,
                    "funding"  => "credit",
                    "last4" => "4242",
                    "metadata"  => [],
                    "name"  => null,
                    "tokenization_method"  => null,
                    "wallet" => null
            ];
        }
    }

    public function country()
    {
        return $this->values->country;
    }

    public function currency()
    {
        return $this->values->currency;
    }

    public function accountName()
    {
        return $this->values->account_holder_name;
    }

    public function accountHolderType()
    {
        return 'individual';
    }

    public function routingNumber()
    {
        return $this->values->swift_code;
    }

    public function accountNumber()
    {
        return $this->values->account_number;
    }

}
