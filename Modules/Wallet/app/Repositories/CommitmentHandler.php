<?php

namespace Modules\Wallet\Repositories;

use Modules\Wallet\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\WalletUser;
use Modules\Wallet\Enums\UserCommitmentType;
use Modules\PaymentMethod\Models\PaymentMethod;
use Modules\Wallet\Repositories\CommitmentsRepository;
use Modules\Wallet\Interfaces\CommitmentsRepositoryInterface;

class CommitmentHandler
{
    private  WalletUser $user;
    private   $contributerId;
    private  UserCommitmentType $commitmentType;
    private CommitmentsRepositoryInterface $commitmentsRepository;
    public function __construct(UserCommitmentType $commitmentType,  CommitmentsRepositoryInterface $commitmentsRepository, $contributer_id=null)
    {
        $this->user                   =     WalletUser::with('wallet')->find(Auth::id());
        $this->contributerId         =      $contributer_id;

        $this->commitmentType         =     $commitmentType;
        
        $this->commitmentsRepository  =     $commitmentsRepository;
       
        
    }

    /**
     * Retrieve commitments based on type (incoming or outgoing).
     */
    public function getCommitments()
    {
        return $this->commitmentType === UserCommitmentType::INCOMING
            ? $this->commitmentsRepository->getIncomingCommitments($this->user)
            : $this->commitmentsRepository->getOutgoingCommitments($this->user);
    }



    /**
     * Prepare a structured response for commitments.
     */
    public function prepareCommitmentsPayload($commitments)
    {
        
         $totalAmount       =  $commitments->sum('amount');

         $weeklyTotal       =  $commitments->where('frequency', 'weekly')->sum('amount');

         $fortnightlyTotal  =  $commitments->where('frequency', 'fortnightly')->sum('amount');
         
         $monthlyTotal      =  $commitments->where('frequency', 'monthly')->sum('amount');

         $mappedCommitments =  $commitments->map(function (Schedule $schedule) {

          

            if( $schedule->receivable_type == 'Modules\Wallet\Models\WalletUser')
            {
                if($this->commitmentType === UserCommitmentType::INCOMING) {

                    $receiver = WalletUser::with('wallet')->find($schedule->user_id);
                    
                    $receiverName = $receiver->first_name . ' ' . $receiver->last_name;

                    $user_id  = $receiver->id;
    
                }

                if($this->commitmentType === UserCommitmentType::OUTGOING) {

                    $receiver = WalletUser::with('wallet')->find($schedule->receivable_id);
                    if ($receiver)
                    {
                            $receiverName = $receiver->first_name . ' ' . $receiver->last_name;

                            $user_id      = $receiver->id;

                    }
                }
            

               
            }
            return [

                'id'                =>    $schedule->id,

                'name'              =>    $receiverName,

                'user_id'           =>    $user_id,

                'amount'            =>    $schedule->amount,

                'currency'          =>    $schedule->currency,

                'type'              =>    $schedule->type,

                'frequency'         =>    $schedule->frequency,

                'start_date'        =>    $schedule->start_date,

                'next_run_date'     =>    $schedule->next_run_date,
                
                'receivable_type'   =>    $schedule->receivable_type,

                'receivable_id'     =>    $schedule->receivable_id,

                'status'            =>    $schedule->schedule_status,
            ];
        });

        
        return [

            'total_amount'      =>  $totalAmount,
            
            'weekly_total'      =>   $weeklyTotal,

            'fortnightly_total' =>   $fortnightlyTotal,
            
            'monthly_total'     =>   $monthlyTotal,

            'commitments'       =>   $mappedCommitments,
        ];

    }

    public function getRecentTransaction()
    {
        $commitments = $this->getCommitments();
        if($this->commitmentType === UserCommitmentType::INCOMING)
        {
            
            $commitments = $commitments->filter(function ($commitment) {

               return $commitment->user_id == $this->contributerId;

            });
           
        }

        if($this->commitmentType === UserCommitmentType::OUTGOING)
        {
            
            $commitments = $commitments->filter(function ($commitment) {
                return $commitment->receivable_id == $this->contributerId;
            });
        }
        
        return $commitments;
    }
    /**
     * Get structured payload for API response.
     */
    public function getPayload()
    {
        $commitments = $this->getCommitments();

        return $this->prepareCommitmentsPayload($commitments);
    }
}
