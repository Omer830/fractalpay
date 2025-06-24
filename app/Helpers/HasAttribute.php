<?php

namespace App\Helpers;

use Modules\Options\Models\Attribute;

trait HasAttribute
{

    public function attribute()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function createAttribute(array $attribute)
    {
        $this->attribute()->createMany($attribute);
    }

}