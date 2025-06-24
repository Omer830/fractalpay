<?php

namespace Modules\Auth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Options\Models\Options;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Notifications\Notifiable;
use Modules\ExpenseTracker\Models\Visit;
use Ellaisys\Cognito\Auth\RegistersUsers;
use Modules\Auth\Traits\hasRefreshTokens;
use Modules\Auth\Traits\hasUserRelations;
use Ellaisys\Cognito\Auth\AuthenticatesUsers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, RegistersUsers, AuthenticatesUsers,
        hasRefreshTokens, hasUserRelations,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'secondary_email',
        'alternative_email',
        'phone_number',
        'otp',
        'secondary_email_otp',
        'alternative_email_otp',
        'email_verified_at',
        'secondary_email_verified_at',
        'alternative_email_verified_at',
        'phone_verified_at',
        'sub',
        'country'
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'sub'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime'
        ];
    }

    protected $appends = ['full_name', 'initials'];

    /** RELATIONSHIPS */
    public function tokens()
    {
        return $this->hasMany(RefreshTokens::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
   



}
