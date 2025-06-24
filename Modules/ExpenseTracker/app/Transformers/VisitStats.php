<?php

namespace Modules\ExpenseTracker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitStats extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $totalBillAmount = $this->bills->sum('amount');
        $total = $this->total;
        $paid = $this->paid;
        $balance = $this->balance;
        $paidPercentage = ($total > 0) ? ($paid / $total) * 100 : 0;

        return [
            'totalBillAmount'         =>  $totalBillAmount,
            'total'                   =>  $total,
            'paid'                    =>  $paid,
            'balance'                 =>  $balance,
            'paid_percentage'         =>  $paidPercentage,
        ];
    }
}
