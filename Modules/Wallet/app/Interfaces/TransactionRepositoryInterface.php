<?php

namespace Modules\Wallet\Interfaces;

use Carbon\Carbon;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\DTO\TransactionDTO;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Enums\TransactionType;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function all(UserDTO $userDTO, TransactionDTO $transactionDTO);

    public function create(UserDTO $userDTO, TransactionDTO $transactionDTO);

    public function update(UserDTO $userDTO, Transaction $transaction, array $data);

    public function getMonthlyTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getWeeklyTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getDailyTransactions(UserDTO $userDTO, int $days): Collection;

    public function getMonthlyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getWeeklyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getDailyReceiveTransactions(UserDTO $userDTO, int $days): Collection;
    
    public function getMonthlySendTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getWeeklySendTransactions(UserDTO $userDTO, Carbon $date): Collection;

    public function getDailySendTransactions(UserDTO $userDTO, int $days): Collection;

    public function getLatestBillsByCategory(UserDTO $userDTO, int $limit = 5,string $type): array;

    public function getCategoryAnalytics(UserDTO $userDTO, string $type): array;
    
    public function getTransactionsByType(
        UserDTO $userDTO,
        string $type, 
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        int $days = 0
    ): Collection;
    
    public function getTransactionSummary(
        UserDTO $userDTO,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): array;


}