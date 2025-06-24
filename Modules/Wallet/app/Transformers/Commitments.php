<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Transformers\PaymentMethodAttribute;

class Commitments extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'type'          =>  Str::headline($this->type),
            'sender_data'        =>  [
                'amount'        =>  $this->sender_amount,
                'currency'       =>  $this->sender_currency,
            ],
            'receiver_data'  =>  [
                'amount'        =>  $this->receiver_amount,
                'currency'       =>  $this->receiver_currency,
            ],
            'frequency'     =>  Str::headline($this->frequency),
            'start_date'    =>  $this->start_date,
            'end_date'      =>  $this->end_date,
            'payment_method'    =>  new GetPaymentMethodResource($this->paymentMethod),
            'receiver'      =>  new ReceiverResource($this->receivable),
            'status'        =>  Str::headline($this->schedule_status),
            'created_at'    =>  $this->created_at
        ];
    }
}
