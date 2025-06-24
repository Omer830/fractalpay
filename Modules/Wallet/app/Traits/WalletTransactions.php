<?php

namespace Modules\Wallet\Traits;

use ErrorException;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Models\WalletUser;
use Modules\Wallet\Models\Transaction;
use Modules\ExpenseTracker\Models\Bill;


use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Enums\TransactionStatus;
use Modules\ExpenseTracker\Interfaces\BillRepositoryInterface;

trait WalletTransactions
{

    public function reserveAvailableBalance($amount)
    {
        $this->available_balance -= $amount;

        $this->save();
    }

    public function increaseAvailableBalance($amount)
    {
        

        $this->available_balance += $amount;
       
        $this->save();
    }

    public function increaseBalance($amount)
    {
        
        $this->balance += $amount;
    
        $this->save();
    }

    public function decreaseBalance($amount)
    {

        $this->balance -= $amount;

        $this->save();
    }
    public function deposit($amount)
    {

        
        $this->balance += $amount;
        $this->available_balance += $amount;
        return $this->save();
    }

    public function withdraw($amount)
    {

        if ($this->available_balance >= $amount) {

            $this->balance -= $amount;

            $this->available_balance -= $amount;

            $this->save();

            return true;
        }

        throw new ErrorException('INSUFFICIENT_FUNDS');
    }


    public function handlePendingTransaction(Transaction $transaction)
    {
        $amount = $transaction->amount;
        
        if($transaction->type === TransactionType::PAYMENT->value)
        {
            if($transaction->is_wallet)
            {
             
                $this->reserveAvailableBalance($amount);

            }


        }
       
        //If user initiate transfer or withdraw
        //Decrease the available balance to reserve funds for sender only
        if ($transaction->type === TransactionType::TRANSFER->value || $transaction->type === TransactionType::WITHDRAW->value) {
            // Deduct from available balance to reserve the amount
            $this->reserveAvailableBalance($amount);
        }

        //If user deposit to their account
        //Increase balance only
        else if($transaction->type === TransactionType::DEPOSIT->value) {
            $this->increaseBalance($amount);
        }

    }

    public function handleCompletedTransaction(Transaction $transaction)
    {
        $amount = $transaction->amount;

        if($transaction->type === TransactionType::PAYMENT->value)
        {


            if($transaction->is_wallet)
            {
                $this->decreaseBalance($amount);
                // InstanceForDependencyInjection
                if ($transaction->receivable_type === 'Modules\ExpenseTracker\Models\Bill') {
                    $this->updateBillPayment($transaction, $amount);
                }

            }


        }
        //If user deposit in their account
        if ($transaction->type === TransactionType::DEPOSIT->value) {
            // Increase available balance: Funds are now available
            $this->increaseAvailableBalance($amount);

        //if user withdraw or transfer
        } elseif ($transaction->type === TransactionType::WITHDRAW->value || $transaction->type === TransactionType::TRANSFER->value) {
            // Withdrawal/Transfer: Deduct from balance, available balance was already adjusted
            $this->decreaseBalance($amount);


            if($receiver = $transaction->receivable) {
                //Update both balance and available_balance
                $receiver->wallet->deposit($amount);
            }

        }
    }

    public function handleFailedTransaction(Transaction $transaction)
    {
        
        $amount = $transaction->amount;

        //If user transferring to other user or withdrawing
        if ($transaction->type === TransactionType::TRANSFER->value || $transaction->type === TransactionType::WITHDRAW->value) {
            // If the transaction fails, restore the reserved amount for sender
            $this->increaseAvailableBalance($amount);
        }
        //if depositing and failed
        else if($transaction->type === TransactionType::DEPOSIT->value) {
            //Decrease balance
            $this->decreaseBalance($amount);
        }


    }

    public function handleCancelledTransaction(Transaction $transaction)
    {
        $amount = $transaction->amount;

        //if user transfer or withdrawing
        if ($transaction->type === TransactionType::TRANSFER->value || $transaction->type === TransactionType::WITHDRAW->value) {
            // If the transaction is cancelled, restore the reserved amount
            $this->increaseAvailableBalance($amount);
        }
        else if($transaction->type === TransactionType::DEPOSIT->value) {
            //Decrease balance
            $this->decreaseBalance($amount);
        }
    }

    public function handleRefundedTransaction(Transaction $transaction)
    {
        $amount = $transaction->amount;

        if ($transaction->type === TransactionType::DEPOSIT->value || $transaction->type === TransactionType::TRANSFER->value) {
            // Refund: Deduct from both balances
            $this->withdraw($amount);
        }
    }

    public static function processTransaction(Transaction $transaction, WalletUser|null $user = null)
    {

        $wallet = self::userWallet($user);

        if(!$wallet) {
            throw new ErrorException('UN_SUCCESSFUL');
        }

        switch ($transaction->status) {
            case TransactionStatus::PENDING->value:
                $wallet->handlePendingTransaction($transaction);
                break;

            case TransactionStatus::COMPLETED->value:
                $wallet->handleCompletedTransaction($transaction);
                break;

            case TransactionStatus::FAILED->value:
                $wallet->handleFailedTransaction($transaction);
                break;

            case TransactionStatus::CANCELLED->value:
                $wallet->handleCancelledTransaction($transaction);
                break;

            case TransactionStatus::REFUNDED->value:
                $wallet->handleRefundedTransaction($transaction);
                break;

            default:
                throw new ErrorException('UNKNOWN_TRANSACTION_STATUS');
        }
    }

    private function updateBillPayment(Transaction $transaction, float $amount)
    {
        
       
        $bill = Bill::findOrFail($transaction->receivable_id);

      
        $data = [
            'paid' => $bill->amount - ($bill->balance - $amount),
            'balance' => $bill->balance - $amount,
        ];

        
        $updatedBill = tap($bill)->update($data);
    }
}
