<?php

namespace Modules\Wallet\DTO;

use App\Exceptions\ErrorException;
use App\Helpers\ExchangeRateHelper;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Options\Enums\Attributes;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionFrequency;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Models\WalletUser;

class CommitmentDTO
{

    private CommitmentDTO $receiver;
    private CommitmentDTO $sender;

    public function __construct(private array $data, private Currency $baseCurrency = Currency::EUR)
    {

        $this->data['receiver'] = WalletUser::with(['country', 'country.attribute', 'wallet'])->find($this->data['receiver_id'] ?? null);
        $this->data['sender'] = WalletUser::with(['country', 'country.attribute', 'wallet'])->find(Auth::id());
    }

    public function getData()
    {

        $sender = $this->sender();
        $receiver = $this->receiver();
        $exchangeRate = $this->getExchangeRate();

        $response = [
            'user_id'           =>  $sender?->id,
            'type'              =>  $this->data['type'],
            'frequency'         =>  $this->data['frequency'] ?? TransactionFrequency::ONE_OFF,
            'payment_method_id' =>  $this->data['payment_method_id'],
            'base_currency'     =>  $this->baseCurrency->value,
            'currency'          =>  $this->data['currency'],
            'amount'            =>  $this->data['amount'],
            'exchange_rate'     =>  $exchangeRate,
            'wallet_id'         =>  $sender->wallet_id,
            'start_date'        =>  $this->data['start_date'] ?? NOW(),
            'end_date'          =>  $this->data['end_date'] ?? null,
            'next_run_date'     =>  $this->data['start_date'] ?? null,
            'receivable_id'     =>  $receiver?->id,
            'description'       =>  $this->data['description'] ?? null,
        ];

        if($receiver) {
            $response['receivable_type']   =  WalletUser::class;
        }

        return $response;

    }

    Public function sender()
    {
        return WalletUser::find(Auth::id());
    }

    public function receiver()
    {
        return $this->data['receiver'] ?? null;
    }

    public function getExchangeRate()
    {
        $transactionCurrency = $this->data['currency'] ?? null;
        return ExchangeRateHelper::getExchangeRate($transactionCurrency);
    }

}