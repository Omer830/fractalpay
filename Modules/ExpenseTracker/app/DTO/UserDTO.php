<?php

namespace Modules\ExpenseTracker\DTO;

use Modules\Auth\Models\User;
use Modules\ExpenseTracker\Models\ExpenseUser;
use Modules\Wallet\Models\WalletUser;

class UserDTO
{

    public ?int $id = null;

    private mixed $name;

    public function __construct(ExpenseUser|User|Null $user = null)
    {
        $this->id = $user?->id;
        $this->name = $user?->full_name;    
    }

    public function user(): ? ExpenseUser
    {
        return ExpenseUser::find($this->id);
    }

    public function id() : int
    {
        return $this->user()?->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }


}