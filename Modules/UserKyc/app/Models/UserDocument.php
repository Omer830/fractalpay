<?php

namespace Modules\UserKyc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserKyc\Database\Factories\UserDocumentFactory;

class UserDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): UserDocumentFactory
    {
        //return UserDocumentFactory::new();
    }
}
