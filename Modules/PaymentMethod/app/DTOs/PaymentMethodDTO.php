<?php

namespace Modules\PaymentMethod\DTOs;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Enums\PaymentMethodTypes;
use Modules\PaymentMethod\Factories\PaymentRepositoryFactory;
use Modules\PaymentMethod\Models\PaymentUser;
use Modules\Wallet\Models\WalletUser;

class PaymentMethodDTO
{

    private $values;
    const BANK_ACCOUNT = 'bank_account';
    const CREDIT_CARD = 'credit_card';

    public function __construct(array $values = [])
    {
        $this->values = (object)$values;
    }

    public function user(): PaymentUser
    {
        return PaymentUser::find(Auth::id());
    }

    public function accountType() : PaymentMethodTypes|null
    {
        return PaymentMethodTypes::tryFrom($this->values->type ?? null);
    }

    public function paymentMethodData()
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'type' => $this->accountType()->value,
        ];
    }

    public function attribute()
    {
        $data = [];

        foreach($this->accountData() as $key => $value)
        {
            $data[] = [
                'key'   =>  $key,
                'value' =>  $value,
            ];
        }

        return $data;
    }

    public function accountData()
    {

        if($this->accountType() === PaymentMethodTypes::CREDIT_CARD) {
            return [
                'account_holder_name' => $this->values->account_name,
                'card_number' => $this->values->card_number,
                'expiry' => $this->values->expiry,
                'cvv' => $this->values->cvv,
            ];
        }
        if($this->accountType() === PaymentMethodTypes::BANK_ACCOUNT)
        {
            return [
                'bank_name' => $this->bankName(),
                'account_holder_name' => $this->accountName(),
                'routing_number' => $this->routingNumber(),
                'account_number' => $this->accountNumber()
            ];
        }
    }

    public function bankName()
    {
        return $this->values->bank_name;
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
        return $this->values->account_name;
    }

    public function accountHolderType()
    {
        return 'individual';
    }

    public function routingNumber()
    {
        return $this->values->bsb;
    }

    public function accountNumber()
    {
        return $this->values->account_number;
    }

    public function getMethodId() {
        return $this->values->payment_method_id;
    }

    public function repository()
    {
        return PaymentRepositoryFactory::getRepository($this->accountType());
    }

}
