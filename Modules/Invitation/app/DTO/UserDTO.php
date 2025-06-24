<?php

namespace Modules\Invitation\DTO;

use Modules\Auth\Models\User;
use Modules\Invitation\Models\InvitedUser;

class UserDTO
{

    public function __construct(private User|null $user)
    {
    }

    public function user()
    {
        return InvitedUser::find($this->user->id);
    }

    public function id()
    {
        return $this->user?->id;
    }

    public function name()
    {
        return $this->user?->full_name;
    }

    public function email()
    {
        return $this->user?->email;
    }

    public function phone()
    {
        return $this->user?->phone_number;
    }

}