<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSingleVisit extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $authUserId = auth()->id();
        return [
            'id'            =>  $this->id,
            'visit_reason'  =>  $this->visit_reason,
            'visit_details' =>  $this->visit_details,
            'name'          =>  $this->kyle,
            'provider_number'   =>  $this->provider_number,
            'organisation'  =>  $this->organisation,
            'visit_type'    =>  $this->visit_type,
            'visit_owner'   =>  $this->owner?->full_name,
            'isOwner'       =>  $this->user_id === $authUserId,
            'bills'         =>  GetBill::collection($this->bills),
            'stats'         =>  new VisitStats($this->resource),
            'contributors'  =>  Contributors::collection($this->contributors),
        ];
    }
}
