<?php

namespace Modules\Invitation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Auth\Models\User;
use Modules\Invitation\Database\Factories\InvitedUserFactory;

class UserInvitation extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'invited_user', 'invitation_code', 'invitation_status', 'email', 'phone_number','name'];

    protected $table = 'invitations';

    protected static function newFactory(): InvitedUserFactory
    {
        //return InvitedUserFactory::new();
    }

    public function scopeFindByCode($query, string $code)
    {
        return $query->where('invitation_code', $code);
    }

    public function user()
    {
        return $this->belongsTo(InvitedUser::class, 'invited_user');
    }
    public function inviter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeHasUser($query)
    {
        return $query->whereHas('user');
    }

    public function scopeWithStatus($query, $statuses)
    {
        return $query->whereIn('invitation_status', $statuses);
    }

}
