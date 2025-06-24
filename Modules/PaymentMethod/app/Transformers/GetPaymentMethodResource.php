<?php

namespace Modules\PaymentMethod\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        =>  $this->id,
            'uuid'      =>  $this->uuid,
            'type'      =>  $this->type,
            'attribute' =>  PaymentMethodAttribute::collection($this->attribute->where('key', '!=', 'cvv')),
        ];
    }
}
