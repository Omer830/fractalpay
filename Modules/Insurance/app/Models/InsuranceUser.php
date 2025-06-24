<?php

namespace Modules\Insurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Traits\hasUserRelations;
use Modules\Insurance\Database\Factories\UserInsuranceFactory;
use Laravel\Cashier\Billable as CashierBillable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lanos\CashierConnect\Billable as ConnectBillable;
use Illuminate\Database\Eloquent\SoftDeletes;


class InsuranceUser extends Authenticatable
{
    use HasFactory, hasUserRelations;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'users';

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected static function newFactory(): UserInsuranceFactory
    {
        //return UserInsuranceFactory::new();
    }
}
