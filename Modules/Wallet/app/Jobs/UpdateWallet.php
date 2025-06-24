<?php

namespace Modules\Wallet\Jobs;

use Exception;
use App\Services\LogService;
use Illuminate\Bus\Queueable;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Models\Wallet;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Models\Transaction;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Wallet\Enums\TransactionStatus;

class UpdateWallet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private UserDTO $userDTO,
        private Transaction $transaction
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $transaction = $this->transaction;
           

            // Process the transaction
            Wallet::processTransaction($transaction, $transaction->user);
        } catch (Exception $e) {
            
            Log::error('Error processing wallet transaction', [
                'transaction_id' => $this->transaction->transaction_id,
                'user_id' => $this->transaction->user_id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            throw $e;  
        }
    }

    // Handle job failure
    public function failed(\Throwable $exception)
    {
        Log::error('Job failed', [
            'transaction_id' => $this->transaction->transaction_id,
            'user_id' => $this->transaction->user_id,
            'error' => $exception->getMessage(),
            'stack_trace' => $exception->getTraceAsString(),
            'exception_class' => get_class($exception),  
        ]);
    }
}
