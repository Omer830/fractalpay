<?php

namespace Modules\ExpenseTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Modules\ExpenseTracker\Traits\hasUserRelations;

class ExpenseUser extends User
{
    use hasUserRelations;

    protected $table = 'users';

}