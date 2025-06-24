<?php

namespace Modules\Auth\Http\Resources;

use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        // Get data from RegisterResource
        $registerData = (new RegisterResource($this->resource->user))->toArray($request);

        // Get data from TimestampResource
        $timestampData = (new TimestampResource($this->resource->user))->toArray($request);

        // Merge both arrays
        return array_merge($registerData, $timestampData);

    }
}
