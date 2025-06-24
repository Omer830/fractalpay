<?php

namespace Modules\Wallet\Services;

use Modules\Wallet\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Log;
use Modules\Wallet\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\DTO\CommitmentDTO;
use Modules\Wallet\Models\WalletUser;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\Enums\TransactionType;
use Modules\Wallet\Enums\TransactionStatus;
use Modules\Wallet\Enums\UserCommitmentType;
use Modules\PaymentMethod\Models\PaymentMethod;
use Modules\Wallet\Repositories\CommitmentHandler;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Wallet\Interfaces\CommitmentsRepositoryInterface;
use Modules\Wallet\Interfaces\TransactionRepositoryInterface;

class CommitmentsService
{

    public function __construct(private CommitmentsRepositoryInterface $CommitmentsRepository,  private InvitedUserService $InvitedUserService,)
    {
        $this->UserDTO = new UserDTO(Auth::user());
    }

    public function createCommitment($data)
    {
        $commitmentDTO = new CommitmentDTO($data);
        return $this->CommitmentsRepository->create($commitmentDTO);
    }

    public static function processCommitment(Schedule $schedule)
    {
        $sender = $schedule->sender;

//        if($schedule->type === TransactionType::TRANSFER->value) {

            $transactionService = app(TransactionService::class, [
                'user' => $sender,
            ]);

            $response = $transactionService->pay($schedule);

            //Todo: only for testing purposes
            if($response instanceof Transaction) {
                $schedule->updateNextRun();

                $transactionService->update($response, ['status' => TransactionStatus::COMPLETED]);
            }

//        }

    }
    

    public function userCommitments()
    {
       
        $incomingHandler = new CommitmentHandler(
            UserCommitmentType::INCOMING,
            $this->CommitmentsRepository
        );
   
        $outgoingHandler = new CommitmentHandler(
            UserCommitmentType::OUTGOING,
            $this->CommitmentsRepository
        );

        $allContributor = ConnectedUsers::collection($this->InvitedUserService->getUserList());
        
        return [
            'incoming' => $incomingHandler->getPayload(),
            'outgoing' => $outgoingHandler->getPayload(),
            'all' => $allContributor,
        ];

    }

    public function getCommitmentDetails($data)
    {
        
        return  $this->CommitmentsRepository->find($data['id']);

    }

    public function getRecentTransactionDetails($data)
    {

       if($data['contributer_type'] == UserCommitmentType::OUTGOING->value)
       {
        $recentTransaction = new CommitmentHandler(
            UserCommitmentType::OUTGOING,
            $this->CommitmentsRepository,
            $data['user_id']
        );
       }
       if($data['contributer_type'] == UserCommitmentType::INCOMING->value)
       {
        
        $recentTransaction = new CommitmentHandler(
            UserCommitmentType::INCOMING,
            $this->CommitmentsRepository,
            $data['user_id']
        );
        
       }
      
    
        return  $recentTransaction->getRecentTransaction();
    }
    public function getLastCommitment()
    {
        $userID = $this->UserDTO->id;
    
        $schedule = $this->CommitmentsRepository->getRecentTransactions($userID);
        
        if (!$schedule) {
            return null;
        }
  
        $schedule->transaction_type = $schedule->user_id == $userID 
            ? 'outgoing' 
            : 'incoming';

        $schedule->formatted_amount = number_format($schedule->amount, 2);
        $schedule->formatted_date = $schedule->created_at->format('Y-m-d H:i:s');
        
        return $schedule;
    }

}