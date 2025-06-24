<?php

namespace Modules\UserKyc\DTO;

use Modules\Auth\Models\User;
use Modules\UserKyc\Models\UserProfile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserDTO implements HasMedia
{
    use InteractsWithMedia;

    public int|null $id;

    private mixed $name;

    public function __construct(User|Null $user = null)
    {
        $this->id = $user?->id;
        $this->name = $user?->full_name;
    }

    public function user(): ? UserProfile
    {
        return UserProfile::find($this->id);
    }

}