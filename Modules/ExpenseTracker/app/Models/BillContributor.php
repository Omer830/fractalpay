<?php

namespace Modules\ExpenseTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BillContributor extends Pivot
{

    protected $table = 'bill_contributor';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'visit_id',
        'contributer_id',
    ];
    
}
