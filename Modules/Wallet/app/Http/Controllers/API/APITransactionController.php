<?php

namespace Modules\Wallet\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Wallet\Contracts\TransactionControllerInterface;
use Modules\Wallet\Http\Requests\CreateTransaction;
use Modules\Wallet\Http\Requests\UpdateTransaction;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Services\TransactionService;
use Modules\Wallet\Http\Controllers\WalletController;

class APITransactionController extends WalletController implements TransactionControllerInterface
{

    public function __construct(private TransactionService $TransactionService)
    {

    }

    public function allTransactions(Request $request)
    {
        return $this->TransactionService->getAllTransactions($request->all());
    }

    public function getTransaction($id)
    {
        // TODO: Implement getTransaction() method.
    }

    public function transferPayToUser(CreateTransaction $request)
    {
        return $this->TransactionService->pay($request->all());
    }

    public function updateStatus(Transaction $transaction, UpdateTransaction $request)
    {
        return $this->TransactionService->update($transaction, $request->all());
    }


}