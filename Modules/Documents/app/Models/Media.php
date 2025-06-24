<?php

namespace Modules\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Documents\Database\Factories\MediaFactory;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): MediaFactory
    {
        //return MediaFactory::new();
    }
}
