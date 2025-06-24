<?php

namespace Modules\ExpenseTracker\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ContributorVisit extends Pivot
{

    protected $table = 'visit_contributor';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'visit_id',
        'contributor_id',
    ];
    
}
