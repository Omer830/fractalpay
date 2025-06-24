<?php

namespace Modules\UserSecurityAnswer\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\SecurityQuestion\App\Models\SecurityQuestion;
use App\Models\User;

class UserSecurityAnswer extends Model
{
    use SoftDeletes; // Enable soft deletes

    protected $fillable = ['user_id', 'security_question_id', 'answer'];
    
    protected $dates = ['deleted_at']; // Track deletion timestamp

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function securityQuestion()
    {
        return $this->belongsTo(SecurityQuestion::class, 'security_question_id');
    }
}
