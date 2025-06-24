<?php

namespace Modules\Wallet\DTO;
use Modules\Auth\Models\User;
use Modules\Wallet\Models\WalletUser;

class UserDTO
{

    public int $id;

    private mixed $name;

    public function __construct(WalletUser|User|Null $user = null)
    {
        $this->id = $user?->id;
        $this->name = $user?->full_name;
    }

    public function user(): ? WalletUser
    {
        return WalletUser::find($this->id);
    }

    public function id() : int
    {
        return $this->user()?->id;
    }

    public function wallet()
    {
        return $this->user()->wallet;
    }

}