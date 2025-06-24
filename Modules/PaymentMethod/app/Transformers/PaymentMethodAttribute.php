<?php

namespace Modules\PaymentMethod\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Enums\PaymentMethodTypes;

class PaymentMethodAttribute extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        $response['key'] = $this->key;

        if($this->key === 'card_number') {
            $response['value'] = Str::substr($this->value, -4);
        }
        else {
            $response['value'] = $this->value;
        }

        return $response;

    }
}
