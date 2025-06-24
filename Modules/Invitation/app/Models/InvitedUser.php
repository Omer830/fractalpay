<?php

namespace Modules\Invitation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;
use Modules\Auth\Traits\hasUserRelations;
use Modules\Invitation\Database\Factories\InvitedUserFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InvitedUser extends User implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $visible = ['id', 'first_name', 'last_name', 'user_name', 'avatar','email'];

    protected static function newFactory(): InvitedUserFactory
    {
        //return InvitedUserFactory::new();
    }

    public function userInvitations()
    {
        return $this->hasMany(UserInvitation::class, 'user_id');
    }

}
