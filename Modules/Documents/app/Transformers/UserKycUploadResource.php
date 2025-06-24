<?php

namespace Modules\Documents\Transformers;

use App\Http\Resources\Common\FractalPayFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserKycUploadResource extends FractalPayFormatter
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
