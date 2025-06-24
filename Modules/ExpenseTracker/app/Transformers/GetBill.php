<?php

namespace Modules\ExpenseTracker\Transformers;

use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetBill extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $authUserId = auth()->id();

        $response = [
            'id'                =>  $this->id,
            'user'              =>  $this->user?->full_name,
            'visit'             =>  new GetVisit($this->visit),
            'category'          =>  $this->category,
            'total'             =>  $this->amount,
            'paid'              =>  $this->paid,
            'balance'           =>  $this->balance,
            'paid_percentage'   =>  ($this->amount > 0) ? ($this->paid / $this->amount) * 100 : 0,
            'description'       =>  $this->description,
            'invoice_number'    =>  $this->invoice_number,
            'due_date'          =>  $this->due_date,
            'already_paid'      =>  $this->already_paid,
            'contributors'      =>  Contributors::collection($this->contributors),
            'isOwner'           =>  $this->user_id === $authUserId,
            'billDocument'      =>  $this->bill_file_url,
        ];

        $timestamp = new TimestampResource($this);
        $response = array_merge($response, $timestamp->toArray($request));
        return $response;
    }
}
