<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Transformers\PaymentMethodAttribute;

class GetCommitment extends JsonResource
{
    protected $contributerType;
    public function __construct($resource, $contributerType)
    {
        parent::__construct($resource);
        
        $this->contributerType = $contributerType;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $payerDetails = ($this->contributerType === 'outgoing')  ? 'Contribution Send'  : (($this->contributerType === 'incoming') ? 'Contribution Received' : '');
        $startDate = $this->start_date ? Carbon::parse($this->start_date) : Carbon::now();
        return [
            'transaction_information'        =>  [

               'transaction_number' =>  $this->id,

                'amount'            =>  $this->amount,

                'date'              =>  $startDate->toDateString(),

            ],

            'payer_details' => [

                'frequency' => $this->frequency,

                'reciever'   => new ReceiverResource($this->receivable),

                'type'       => $payerDetails ,

            ],

            'payment_method'        =>  [

                'method'     =>   $this->paymentMethod->type,

                'attribute' =>    PaymentMethodAttribute::collection($this->paymentMethod->attribute->where('key', '!=', 'cvv')),
                
            ],
            
        ];
    }
 
}
