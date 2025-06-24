<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitByCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'visit_reason'  =>  $this->visit_reason,
            'visit_details' =>  $this->visit_details,
            'stats'         =>  new VisitStats($this->resource),
            'bills'         =>  GetBill::collection($this->bills)
        ];
    }
}
