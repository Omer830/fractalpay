<?php

namespace Modules\Auth\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshTokens extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'AccessToken', 'RefreshToken', 'IdToken', 'ExpiresIn'
    ];

    public function getIsExpiredAttribute()
    {
        $expiresIn = $this->ExpiresIn; // seconds
        $expiryTime = $this->updated_at->addSeconds($expiresIn);

        return Carbon::now()->greaterThan($expiryTime);
    }
}
