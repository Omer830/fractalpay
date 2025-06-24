<?php

namespace Modules\Wallet\Interfaces;

use Modules\Wallet\DTO\CommitmentDTO;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Models\WalletUser;

interface CommitmentsRepositoryInterface
{

    public function create($commitmentDTO);

    public function getIncomingCommitments(WalletUser $user);

    public function getOutgoingCommitments(WalletUser $user);

    public function find(int $commitmentId);
    
    public function getRecentTransactions(int $userId);
}