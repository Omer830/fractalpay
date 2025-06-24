<?php

namespace Modules\Wallet\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Transformers\PaymentMethodAttribute;

class GetLatestCommitments extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'transaction_type' => $this->transaction_type,
            'amount' => $this->formatted_amount ?? number_format($this->amount, 2),
            'currency' => $this->currency,
            'status' => $this->schedule_status ?? 'active',
            'frequency' => $this->frequency,
            'payment_method' => $this->paymentMethod ? $this->paymentMethod->name : null,
            'start_date' => $this->formatDate($this->start_date),
            'next_run_date' => $this->formatDate($this->next_run_date),
            'counterparty' => $this->getCounterparty(),
            'created_at' => $this->formatted_date ?? $this->formatDate($this->created_at, 'Y-m-d H:i:s')
        ];
    }

    protected function formatDate($date, string $format = 'Y-m-d'): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function getCounterparty(): array
    {
        if ($this->transaction_type == 'outgoing') {
            return [
                'type' => $this->receivable_type ? class_basename($this->receivable_type) : 'Unknown',
                'id' => $this->receivable_id,
                'name' => $this->receivable->name ?? 'Unknown'
            ];
        }

        return [
            'type' => 'User',
            'id' => $this->user_id,
            'name' => $this->user->name ?? 'Unknown'
        ];
    }
 
}
