<?php

namespace Modules\Wallet\Services;


use Carbon\Carbon;
use Modules\Wallet\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\WalletUser;
use Modules\Wallet\DTO\TransactionDTO;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Collection;
use Modules\Wallet\Interfaces\TransactionRepositoryInterface;

class TransactionService
{

    public function __construct(
        private TransactionRepositoryInterface $TransactionRepository,
        private WalletUser|null $user = null
    )
    {
        
        $this->userDTO = new UserDTO($user?->id ? $user : Auth::user());
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAllTransactions($data)
    {
        $transactionData = new TransactionDTO($data);
        return $this->TransactionRepository->all($this->userDTO, $transactionData);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function pay($data)
    {
        $transactionData = new TransactionDTO($data);

        return $this->TransactionRepository->create($this->userDTO, $transactionData);
    }

    public function update(Transaction $transaction, $data)
    {

        if($transaction->user_id !== $this->userDTO->id()) {
            throw new ErrorException('NOT_ALLOWED');
        }

        if($transaction->status !== TransactionStatus::PENDING->value) {
            throw new ErrorException('STATUS_CHANGE_NOT_ALLOWED');
        }

        return $this->TransactionRepository->update($this->userDTO, $transaction, $data);
    }


    public function getMonthlyTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->TransactionRepository->getMonthlyTransactions($userDTO, $date);
    }

    public function getMonthlyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
    
        return $this->TransactionRepository->getMonthlyReceiveTransactions($userDTO, $date);
    }

    public function getMonthlySendTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->TransactionRepository->getMonthlySendTransactions($userDTO, $date);
    }

 
    public function getWeeklyTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->TransactionRepository->getWeeklyTransactions($userDTO, $date);
    }

    public function getWeeklyReceiveTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->TransactionRepository->getWeeklyReceiveTransactions($userDTO, $date);
    }

    public function getWeeklySendTransactions(UserDTO $userDTO, Carbon $date): Collection
    {
        return $this->TransactionRepository->getWeeklySendTransactions($userDTO, $date);
    }

    public function getDailyTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->TransactionRepository->getDailyTransactions($userDTO, $days);
    }

    public function getDailyReceiveTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->TransactionRepository->getDailyReceiveTransactions($userDTO, $days);
    }

    public function getDailySendTransactions(UserDTO $userDTO, int $days): Collection
    {
        return $this->TransactionRepository->getDailySendTransactions($userDTO, $days);
    }

      /**
     * Get latest paid bills with category information
     */
    public function getLatestBillsByCategory(UserDTO $userDTO,$type): array
    {
        $limit = 5;
        return $this->TransactionRepository->getLatestBillsByCategory($userDTO, $limit, $type);
    }

    /**
     * Get spending analytics by category
     */
    public function getCategoryAnalytics(UserDTO $userDTO,$type): array
    {
        return $this->TransactionRepository->getCategoryAnalytics($userDTO,$type);
    }

    /**
     * Get complete bill payment analytics
     */
    public function getBillPaymentAnalytics(UserDTO $userDTO): array
    {
        return [
            'transactionReceive' => $this->getLatestBillsByCategory($userDTO,'receive'),
            'TransactionSend' => $this->getLatestBillsByCategory($userDTO,'send'),
            'category_analyticsSend' => $this->getCategoryAnalytics($userDTO,'send'),
            'category_analyticsReceive' => $this->getCategoryAnalytics($userDTO,'receive'),
            'summary' => $this->TransactionRepository->getTransactionSummary($userDTO, now()->subMonth(), now())
        ];
    }

}