<?php

namespace Modules\ExpenseTracker\Models;

use Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model 
{

    protected $table = 'organisations';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',        
        'address',     
        'district',   
        'postcode',    
        'state',     
    ];
    
    public function providers()
    {
        return $this->hasMany(Providers::class);
    }
}
