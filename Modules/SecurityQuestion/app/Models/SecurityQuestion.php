<?php

namespace Modules\SecurityQuestion\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityQuestion extends Model
{
    use SoftDeletes; // Enable Soft Deletes

    protected $fillable = ['question'];

    protected $dates = ['deleted_at']; // Add deleted_at field
}
