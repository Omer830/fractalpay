<?php

namespace Modules\Wallet\Contracts;

use Illuminate\Http\Request;
use Modules\Wallet\Http\Requests\CreateTransaction;
use Modules\Wallet\Http\Requests\UpdateTransaction;
use Modules\Wallet\Models\Transaction;

interface TransactionControllerInterface
{

    public function allTransactions(Request $request);

    public function getTransaction($id);

    public function transferPayToUser(CreateTransaction $request);

    public function updateStatus(Transaction $transaction, UpdateTransaction $request);

}