<?php

namespace Modules\Options\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Options\Transformers\OptionAttributes;

class OptionsList extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $response = [
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'slug'  =>  $this->slug,
            'category'  =>  $this->category,
            'parent_slug'   =>  $this->parent_slug,
            'children'    =>  $this->when($this->children, OptionsList::collection($this->children)),
        ];

        $response['attributes'] = $this->whenLoaded('attributes', function() {
            return OptionAttributes::make($this->attributes);
        });


        return $response;
    }
}