<?php

namespace Modules\Documents\Transformers;

use App\Http\Resources\Common\FractalPayFormatter;
use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileImageResource extends FractalPayFormatter
{
    /**
     * Transform the resource into an array.
     */
    public function format(Request $request): array
    {

        return [
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'url'   =>  $this->original_url
        ];

    }
}
