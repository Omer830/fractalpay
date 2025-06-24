<?php


namespace Modules\Wallet\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Wallet\Contracts\TransactionControllerInterface;
use Modules\Wallet\Http\Requests\CreateTransaction;
use Modules\Wallet\Http\Requests\UpdateTransaction;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Services\TransactionService;
use Modules\Wallet\Http\Controllers\WalletController;

class WebTransactionController extends WalletController implements TransactionControllerInterface
{

    public function __construct(private TransactionService $TransactionService)
    {

    }

    public function allTransactions(Request $request)
    {
        try {
            $transactions = $this->TransactionService->getAllTransactions($request->all());

            return Inertia::render('TransactionHistory/TransactionHistory', [
                'transactions' => $transactions,
            ]);

        } catch (\App\Exceptions\ErrorException $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function transactionHistoryPage()
    {
        return Inertia::render('TransactionHistory/TransactionHistory');
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


