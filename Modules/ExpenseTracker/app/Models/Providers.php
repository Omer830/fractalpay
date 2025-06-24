<?php

namespace Modules\ExpenseTracker\Models;

use Modules\Auth\Models\User;
use NunoMaduro\Collision\Provider;
use Illuminate\Database\Eloquent\Model;

class Providers extends Model 
{

    protected $table = 'providers';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'provider_number',  
        'name',            
        'phone',           
        'organisation_id', 
    ];
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
