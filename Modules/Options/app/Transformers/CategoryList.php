<?php

namespace Modules\Options\Transformers;

use App\Http\Resources\Capture\Assets\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryList extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        $categories = $this->groupBy('category');
        $result = [];


        foreach ($categories as $category => $items) {
            $result[$category] = OptionsList::collection($items);
        }

        return $result;

    }
}
