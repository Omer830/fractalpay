<?php

namespace Modules\Options\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;


class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['key', 'value', 'attributable_type', 'attributable_id'];

    protected static function newFactory()
    {
        //return AttributeFactory::new();
    }

    public function attributable()
    {
        return $this->morphTo();
    }

    public static function findAttribute($id, Categories $category , Attributes $attributes)
    {
        $option = Options::findById($id, $category);

        if(!$option) {
            return null;
        }

        $attribute = $option->attribute()->where('key', $attributes)->first();

        if(!$attribute) {
            return null;
        }

        return $attribute->value;
    }

}