<?php

namespace Modules\Wallet\DTO;

use App\Helpers\ExchangeRateHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Wallet\Enums\Currency;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Models\Schedule;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Models\Wallet;
use Modules\Wallet\Models\WalletUser;

class TransactionDTO
{
    public function __construct(
        private array|Schedule $data,
        private TransactionType|null $type = null
    )
    {
    }



    public function createPayload()
    {
        
        $response = [
            'user_id' => $this->data?->user_id ?? Auth::id(),
            'transaction_id'  =>  $this->generateTransactionId(),
            'type'  =>  $this->getType() ?? TransactionType::UNKNOWN->value,
            'base_currency' => $this->getBaseCurrency(),
            'currency' => $this->currency(),
            'amount' => $this->getAmount(),
            'is_wallet' => $this->data?->is_wallet ?? false,
        ];

        $response = $this->getReceiver($response);
        $response = $this->getSenderAmount($response);
        $response = $this->getReceiverAmount($response);

        if(ISSET($response['receiver_amount']) && ISSET($response['sender_amount'])) {
            $response['conversion_rate'] = $response['receiver_amount'] / $response['sender_amount'];
        }
        else if(ISSET($response['sender_amount'])) {
            $response['conversion_rate'] = 1;
        }

        return $response;
    }

    public function getUser($id)
    {
        return WalletUser::find($id);
    }

    public function getAmount()
    {
        return $this->data?->amount ?? $this->data['amount'] ?? 0;
    }

    private function getExchangeRate()
    {
        return $this->data?->exchange_rate ?? $this->data['exchange_rate'] ?? 1;
    }

    public function getSenderAmount($response)
    {
        if($this->data instanceof Schedule) {
            $sender = $this->data->sender;
        }
        else {
            $sender = $this->getUser($this->data['user_id'] ??  null);
        }

        if(!$sender) {
            return $response;
        }

        list($currency, $amount) = $this->userAmount($sender);

        $response['sender_currency'] = $currency;
        $response['sender_amount']   =  $amount;

        return $response;

    }

    public function getReceiverAmount($response)
    {
        if($this->data instanceof Schedule) {
            $receiver = $this->data->receivable;
        }
        else {
            $receiver = $this->getUser($this->data['receivable_id'] ?? null);
        }

        if(!$receiver) {
            return $response;
        }

        list($currency, $amount) = $this->userAmount($receiver);


        $response['receiver_currency'] = $currency;
        $response['receiver_amount']   =  $amount;

        return $response;

    }

    private function userAmount($user)
    {
        $userCurrency = $user?->country?->currency ?? $user?->wallet?->currency ?? env('DEFAULT_CURRENCY');
        list ($userExchangeRate, $userAmount) = ExchangeRateHelper::getAmount($userCurrency, $this->data['amount'], $this->getExchangeRate());
        return [$userCurrency, $userAmount];
    }

    public function getType()
    {
        return $this->type ?? $this->data?->type ?? $this->data['type'] ?? null;
    }

    private function getBaseCurrency()
    {
        return $this->data?->base_currency ?? $this->data['base_currency'] ?? Currency::EUR->value;
    }

    public function getReceiver(array $response)
    {
        if(ISSET($this->data['receiver_id'])) {
            $response['receivable_id'] = $this->data['receiver_id'];
            $response['receivable_type'] = WalletUser::class;
        }

        if($receiverId = $this->data?->receivable_id) {
            $response['receivable_id'] = $receiverId;
            $response['receivable_type'] = $this->data?->receivable_type ?? WalletUser::class;
        }

        return $response;
    }

    public function getFilters()
    {
        return $this->data['filters'] ?? [];
    }

    public function currency()
    {
        return Currency::tryFrom($this->data?->currency ?? $this->data['currency'] ?? 'AUD')?->value;
    }

    public function generateTransactionId()
    {
        // Generate a random alphanumeric string of 17 characters
        $transactionId = strtoupper(Str::random(17));

        $count = 0;
        while (Transaction::where('transaction_id', $transactionId)->exists()) {

            $length = 17;

            if($count > 20) {
                $length = 18;
            }

            $transactionId = strtoupper(Str::random($length));
            $count++;
        }

        return $transactionId;
    }


}