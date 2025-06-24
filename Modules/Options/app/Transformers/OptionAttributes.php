<?php

namespace Modules\Options\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionAttributes extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return $this->transformAttributes($this->resource);
    }

    protected function transformAttributes($attributes)
    {
        return $attributes->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();
    }
}
