<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Transformers\PaymentMethodAttribute;

class GetRecentTransaction extends JsonResource
{
    public static $contributerType = null;

    public static function setContributerType($type)
    {
        self::$contributerType = $type;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $status = self::$contributerType === 'outgoing'
            ? 'Sent'
            : (self::$contributerType === 'incoming' ? 'Receive' : 'Unknown');
       
        $startDate  =  $this->start_date ? Carbon::parse($this->start_date)->toDateString() : 'N/A';

        $endDate    =  $this->end_date ? Carbon::parse($this->end_date)->toDateString() : 'N/A';

        return [
            'user'    => new ReceiverResource(self::$contributerType == 'outgoing' ? $this->receivable : $this->sender),
            'type'    => $this->frequency ?? 'N/A',
            'status'  => $status,
            'amount'  => $this->amount ?? '0.00',
            // 'visit_bills' => $this->id ?? 'N/A',
            'details' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ];
    }
 
}
