<?php

namespace Modules\Wallet\Traits;
use \Modules\Auth\Traits\hasUserRelations as baseHasUserRelations;
use Modules\Wallet\Models\Schedule;
use Modules\Wallet\Models\Wallet;

trait hasUserRelations  {

    use baseHasUserRelations;

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function getWalletIdAttribute()
    {
        return $this->wallet?->id;
    }

    public function commitments()
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }

}