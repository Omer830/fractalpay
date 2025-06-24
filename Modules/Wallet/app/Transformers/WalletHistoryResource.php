<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $now = now();
        
        return [
            'data' => [
                'periods' => [
                    'monthly' => [
                        'receive' => $this->resource['monthly']['receive'] ?? 0,
                        'send' => $this->resource['monthly']['send'] ?? 0,
                        'period' => $now->format('F Y'), 
                        'start_date' => $now->copy()->startOfMonth()->format('Y-m-d'),
                        'end_date' => $now->copy()->endOfMonth()->format('Y-m-d')
                    ],
                    'weekly' => [
                        'receive' => $this->resource['weekly']['receive'] ?? 0,
                        'send' => $this->resource['weekly']['send'] ?? 0,
                        'period' => $now->copy()->startOfWeek()->format('M j') . ' - ' . 
                                    $now->copy()->endOfWeek()->format('M j, Y'), 
                        'start_date' => $now->copy()->startOfWeek()->format('Y-m-d'),
                        'end_date' => $now->copy()->endOfWeek()->format('Y-m-d')
                    ],
                    'daily' => [
                        'receive' => $this->resource['daily']['receive'] ?? 0,
                        'send' => $this->resource['daily']['send'] ?? 0,
                        'period' => $now->format('F j, Y'), 
                        'date' => $now->format('Y-m-d')
                    ]
                ],
                'net_balance' => $this->calculateNetBalance(),
                'currency' => 'AUD',
                'last_updated' => $now->toDateTimeString()
            ],
            
        ];
    }

    protected function calculateNetBalance(): array
    {
        $receive = ($this->resource['monthly']['receive'] ?? 0) 
                 + ($this->resource['weekly']['receive'] ?? 0) 
                 + ($this->resource['daily']['receive'] ?? 0);

        $send = ($this->resource['monthly']['send'] ?? 0) 
              + ($this->resource['weekly']['send'] ?? 0) 
              + ($this->resource['daily']['send'] ?? 0);

        return [
            'total_receive' => $receive,
            'total_send' => $send,
            'balance' => $receive - $send,
            'is_positive' => ($receive - $send) >= 0
        ];
    }


}
