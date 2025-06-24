<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitContributor extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visit_reason' => $this->visit_reason,
            'visit_details' => $this->visit_details,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'provider_number' => $this->provider_number,
            'organisation' => $this->organisation,
            'visit_type' => $this->visit_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
