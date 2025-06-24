<?php

namespace Modules\Insurance\DTO;

use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\User;
use Modules\Auth\Traits\hasUserRelations;
use Modules\Insurance\Models\InsuranceUser;
use Illuminate\Database\Eloquent\SoftDeletes;

readonly class UserDTO
{
    // use SoftDeletes;
    public int $id;

    private mixed $name;

    public InsuranceUser $user;

    public function __construct(User|Null $user = null)
    {
        $user = $user ?? Auth::user();
        $this->user = InsuranceUser::find($user->id);
        $this->id = $this->user?->id;
    }

    public function insurance()
    {
        return $this->user->insurance;
    }

    public function has_insurance() : bool
    {
        return (bool) $this->user->has_insurance;
    }

}