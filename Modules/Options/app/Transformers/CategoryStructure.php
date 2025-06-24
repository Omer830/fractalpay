<?php

namespace Modules\Options\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Options\Models\Options;

class CategoryStructure extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'category'  =>  $this->category,
            'has_children'  =>  (bool) $this->children_count
        ];
    }
}
