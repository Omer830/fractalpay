<?php

namespace Modules\PaymentMethod\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable as CashierBillable;
use Modules\Auth\Models\User;
use Laravel\Cashier\Billable;
use Lanos\CashierConnect\Billable as ConnectBillable;

class PaymentUser extends User
{
    use HasFactory, Billable;
    use CashierBillable;
    use ConnectBillable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function paymentMethod()
    {
        return $this->morphMany(PaymentMethod::class, 'payable');
    }
}
