<?php

namespace Modules\Wallet\Repositories;

use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Models\Schedule;
use Modules\Wallet\DTO\CommitmentDTO;
use Modules\Wallet\Models\WalletUser;

class CommitmentsRepository implements \Modules\Wallet\Interfaces\CommitmentsRepositoryInterface
{
    public function __construct()
    {

    }

    public function create($commitmentDTO)
    {
        $commitment = $commitmentDTO->sender()->commitments()
                    ->create($commitmentDTO->getData());

        return $commitment->refresh();
    }

    public function getIncomingCommitments(WalletUser $user)
    {
      
        return Schedule::query()
            ->where('receivable_type', WalletUser::class)
            ->where('receivable_id', $user->id)
            ->get();
    }

   
    public function getOutgoingCommitments(WalletUser $user)
    {
           
        return Schedule::query()
            ->where('user_id', $user->id)
            ->where('receivable_type', WalletUser::class)
            ->get();
    }

    public function find(int $commitmentId)
    {
        return Schedule::findOrFail($commitmentId);
    }

    public function getRecentTransactions(int $userId)
    {
        return Schedule::where('user_id', $userId)
        ->orWhere('receivable_id', $userId)
        ->with([
            'user' => function($query) {
                $query->select('id', 'first_name', 'last_name' , 'email');
            },
            'receivable',
            'paymentMethod'
        ])
        ->orderBy('created_at', 'desc')
        ->first(); 
    }
}