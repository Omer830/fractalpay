<?php

namespace Modules\Auth\Http\Resources;

use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->id,
            'name'  =>  $this->full_name,
            'initials'  =>  $this->initials
        ];
    }
}
