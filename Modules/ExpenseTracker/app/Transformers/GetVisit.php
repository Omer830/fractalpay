<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetVisit extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $authUserId = auth()->id();

        $totalAmount = $this->bills()->sum('amount');
        $totalPaid   = $this->bills()->sum('paid');
        $paidPercentage = $totalAmount > 0 ? round(($totalPaid / $totalAmount) * 100, 2) : 0;

        $response = [
            'id'              =>  $this->id,
            'visit_reason'    =>  $this->visit_reason,
            'visit_details'   =>  $this->visit_details,
            'name'            =>  $this->name,
            'provider_number' =>  $this->provider_number,
            'organisation'    =>  $this->organisation,
            'visit_type'      =>  $this->visit_type,
            'isOwner'         =>  $this->user_id === $authUserId,
            'stats'           =>  new VisitStats($this->resource),
            'contributors'    =>  Contributors::collection($this->contributors),
            'isOwner'         =>  $this->user_id === $authUserId,
            'paid_percentage' => $paidPercentage,
        ];
        return $response;
    }
}
