<?php

namespace Modules\Wallet\Repositories;

use Carbon\Carbon;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Jobs\UpdateWallet;
use Modules\Wallet\DTO\TransactionDTO;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Enums\TransactionType;
use Illuminate\Database\Eloquent\Collection;
use Modules\ExpenseTracker\Services\BillService;

class TransactionRepository implements \Modules\Wallet\Interfaces\TransactionRepositoryInterface
{
    private $query;
    
    private ?BillService $BillService;

    public function __construct(?BillService $BillService = null)
    {
        $this->BillService = $BillService;
        $this->query = Transaction::query();
    }

    public function all(UserDTO $userDTO, TransactionDTO $transactionDTO)
    {
        $inComing = $this->incoming($userDTO, $transactionDTO);
        $outGoing = $this->outgoing($userDTO, $transactionDTO);
        return $outGoing->merge($inComing);
    }

    public function outgoing(UserDTO $userDTO, TransactionDTO $transactionDTO)
    {
        return $userDTO->user()
            ->outgoingTransactions()
            ->filter($transactionDTO)
            ->get();
    }

    public function incoming(UserDTO $userDTO, TransactionDTO $transactionDTO)
    {
        return $userDTO->user()
            ->incomingTransactions()
            ->filter($transactionDTO)
            ->get();
    }

    public function create(UserDTO $userDTO, TransactionDTO $transactionDTO)
    {
       
        $transaction = $this->query
                ->create($transactionDTO->createPayload());
        Log::info('transaction payload', ['payload' => $transactionDTO->createPayload()]);

        Wallet::processTransaction($transaction->refresh(), $userDTO->user());

        return $transaction->refresh();

    }

    public function update(UserDTO $userDTO, Transaction $transaction, array $data)
    {
        
        $update = $transaction->update($data);

        UpdateWallet::dispatch($userDTO, $transaction->refresh());

        return $update;
    }

    protected function queryBase(UserDTO $userDTO)
    {
        return Transaction::with(['receivable'])
            ->where(function($query) use ($userDTO) {
                $query->where('user_id', $userDTO->id)
                    ->orWhereHas('receivable', function($q) use ($userDTO) {
                        $q->where('user_id', $userDTO->id);
                    });
            });
    }

    protected function queryReceive(UserDTO $userDTO)
    {
        $userBills = $this->BillService->getUserBills($userDTO);
        
        return Transaction::with(['receivable'])
            ->where('receivable_type', 'Modules\ExpenseTracker\Models\Bill')
            ->whereIn('receivable_id', $userBills->pluck('id'))
            ->where('user_id', '!=', $userDTO->id);
    }

    protected function querySend(UserDTO $userDTO)
    {
        return Transaction::with(['receivable'])
            ->where('receivable_type', 'Modules\ExpenseTracker\Models\Bill')
            ->where('user_id', $userDTO->id);
    }

    public function getMonthlyTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->queryBase($userDTO)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->get();
    }

    public function getMonthlyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->queryReceive($userDTO)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->get();
    }

    public function getMonthlySendTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->querySend($userDTO)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->get();
    }

    public function getWeeklyTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->queryBase($userDTO)
            ->whereBetween('created_at', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek()
            ])
            ->get();
    }

    public function getWeeklyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->queryReceive($userDTO)
            ->whereBetween('created_at', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek()
            ])
            ->get();
    }

    public function getWeeklySendTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->querySend($userDTO)
            ->whereBetween('created_at', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek()
            ])
            ->get();
    }

    public function getDailyTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->queryBase($userDTO)
            ->where('created_at', '>=', now()->subDays($days))
            ->get();
    }

    public function getDailyReceiveTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->queryReceive($userDTO)
            ->where('created_at', '>=', now()->subDays($days))
            ->get();
    }

    public function getDailySendTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->querySend($userDTO)
            ->where('created_at', '>=', now()->subDays($days))
            ->get();
    }

    public function getTransactionsByType(
        UserDTO $userDTO,
        string $type,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        int $days = 0
    ): Collection {
        $query = ($type === 'receive') 
            ? $this->queryReceive($userDTO)
            : $this->querySend($userDTO);

    
        if ($days > 0) {
            $query->where('created_at', '>=', now()->subDays($days));
        }


        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->get();
    }

    
    public function getTransactionSummary(
        UserDTO $userDTO,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): array {
    
        $receiveQuery = $this->queryReceive($userDTO);

        $sendQuery = $this->querySend($userDTO);

    
        if ($startDate && $endDate) {
            $receiveQuery->whereBetween('created_at', [$startDate, $endDate]);
            $sendQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'receive' => $receiveQuery->sum('amount'),
            'send' => $sendQuery->sum('amount'),
        ];
    }

    public function getLatestBillsByCategory(UserDTO $userDTO, int $limit = 5, string $type): array
    {

        $query = ($type === 'receive') 
            ? $this->queryReceive($userDTO) 
            : $this->querySend($userDTO);

        return $query->with(['receivable'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'paid_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'bill' => [
                        'id' => $transaction->receivable->id,
                        'description' => $transaction->receivable->description,
                        'category' => $transaction->receivable->category ?? 'Uncategorized',
                        'invoice_number' => $transaction->receivable->invoice_number
                    ]
                ];
            })->toArray();
    }
    
    public function getCategoryAnalytics(UserDTO $userDTO, string $type): array
    {
        
        $query = ($type === 'receive') 
            ? $this->queryReceive($userDTO)
            : $this->querySend($userDTO);

        $transactions = $query->with(['receivable'])
            ->where('created_at', '>=', now()->subMonth())
            ->get();

        $categories = $transactions->groupBy(function ($transaction) {
            return $transaction->receivable->category ?? 'Uncategorized';
        });

        return $categories->map(function ($categoryTransactions, $categoryName) {
            return [
                'category' => $categoryName,
                'total_amount' => $categoryTransactions->sum('amount'),
                'transaction_count' => $categoryTransactions->count(),
                'latest_transaction' => $categoryTransactions->max('created_at')->format('Y-m-d H:i:s')
            ];
        })->sortByDesc('total_amount')->values()->toArray();
    }
}