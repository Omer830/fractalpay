<?php

namespace Modules\Wallet\Models;

use Laravel\Cashier\Billable as CashierBillable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lanos\CashierConnect\Billable as ConnectBillable;
use Modules\Wallet\Traits\hasUserRelations;


class WalletUser extends Authenticatable
{
    use CashierBillable;
    use ConnectBillable;
    use hasUserRelations;

    protected $table = 'users';

    protected $fillable = [];

    public function outgoingTransactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function incomingTransactions()
    {
        return $this->morphMany(Transaction::class, 'receivable');
    }

    public function getDateOfBirthArrayAttribute() : array
    {

        if(!$this->date_of_birth) {
            return [];
        }
        $dateOfBirth = $this->date_of_birth;
        $dateParts = explode('-', $dateOfBirth);

        if(count($dateParts) == 2) {
            return [];
        }

        return [
            'day' => (int) $dateParts[2],
            'month' => (int) $dateParts[1],
            'year' => (int) $dateParts[0]
        ];

    }

    public function getWalletAmountAttribute()
    {
        return $this->wallet;
    }

    public function updateWalletAmount()
    {
        $walletAmount = $this->wallet();
    }

}