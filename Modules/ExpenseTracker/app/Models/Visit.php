<?php

namespace Modules\ExpenseTracker\Models;

use Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model 
{

    protected $table = 'visits';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'visit_reason',
        'visit_details',
        'name',
        'provider_number',
        'organisation',
        'visit_type'
    ];

    public function owner()
    {
        return $this->belongsTo(ExpenseUser::class, 'user_id');
    }
    
    public function contributors()
    {
        return $this->belongsToMany(User::class, 'visit_contributor', 'visit_id', 'contributor_id')
                    ->using(ContributorVisit::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function getTotalAttribute()
    {
        return $this->bills()->sum('amount');
    }

    public function getPaidAttribute()
    {
        return $this->bills()->sum('paid');
    }

    public function getBalanceAttribute()
    {
        return $this->bills()->sum('balance');
    }
}
