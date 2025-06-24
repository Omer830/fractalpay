<?php

namespace Modules\Documents\Transformers;

use App\Http\Resources\Common\FractalPayFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Files extends FractalPayFormatter
{
    /**
     * Transform the resource into an array.
     */
    public function format(Request $request): array
    {
        $data = $this;
        return [
            'id'                =>  $data['id'],
            'name'              =>  $data['name'],
            'url'               =>  $data['url'],
            'side'              =>  $data['custom_properties']['side'] ?? null,
            'custom_properties' =>  $data['custom_properties']
        ];
    }
}
