<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'spendable'   =>  $this->balance,
            'available_balance' =>  $this->available_balance
        ];
    }
}
