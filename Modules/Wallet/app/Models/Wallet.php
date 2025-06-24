<?php

namespace Modules\Wallet\Models;

use App\Exceptions\ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Traits\WalletTransactions;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Wallet extends Model
{
    use HasFactory, WalletTransactions, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'balance',
        'available_balance',
    ];

    public function user()
    {
        return $this->belongsTo(WalletUser::class, 'user_id');
    }

    public static function userWallet($user = null) : self|null
    {
        return self::where('user_id', $user?->id ?? Auth::id())->first();
    }

    public static function balance()
    {
        return self::userWallet()->balance;
    }

    public static function availableBalance()
    {
        return self::userWallet()->available_balance;
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Wallet')
            ->setDescriptionForEvent(function ($event) {
                return "Wallet " . $event;
            })
            ->logOnly(['balance', 'available_balance']);
    }
}
