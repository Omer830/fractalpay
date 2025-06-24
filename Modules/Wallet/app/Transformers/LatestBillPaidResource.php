<?php

namespace Modules\Wallet\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LatestBillPaidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'transactions' => [
                    'receive' => $this->formatTransactions($this->resource['transactionReceive'] ?? []),
                    'send' => $this->formatTransactions($this->resource['TransactionSend'] ?? [])
                ],
                'category_analytics' => [
                    'receive' => $this->formatCategoryAnalytics($this->resource['category_analyticsReceive'] ?? []),
                    'send' => $this->formatCategoryAnalytics($this->resource['category_analyticsSend'] ?? [])
                ],
                'summary' => [
                    'total_receive' => (float)($this->resource['summary']['receive'] ?? 0),
                    'total_send' => (float)($this->resource['summary']['send'] ?? 0),
                    'net_balance' => (float)(($this->resource['summary']['receive'] ?? 0) - ($this->resource['summary']['send'] ?? 0))
                ]
            ],
            'meta' => [
                'currency' => 'AUD',
                'period' => 'Last 30 days',
                'as_of' => now()->toDateTimeString()
            ]
        ];
    }

    protected function formatTransactions(array $transactions): array
    {
        return array_map(function ($transaction) {
            return [
                'id' => $transaction['id'] ?? null,
                'amount' => (float)($transaction['amount'] ?? 0),
                'paid_at' => isset($transaction['paid_at']) 
                    ? Carbon::parse($transaction['paid_at'])->format('Y-m-d H:i:s')
                    : null,
                'bill' => [
                    'id' => $transaction['bill']['id'] ?? null,
                    'description' => $transaction['bill']['description'] ?? null,
                    'category' => $transaction['bill']['category'] ?? 'Uncategorized',
                    'invoice_number' => $transaction['bill']['invoice_number'] ?? null
                ]
            ];
        }, $transactions);
    }
    protected function formatCategoryAnalytics(array $analytics): array
    {
        return array_map(function ($category) {
            return [
                'category' => $category['category'] ?? 'Uncategorized',
                'total_amount' => (float)($category['total_amount'] ?? 0),
                'transaction_count' => $category['transaction_count'] ?? 0,
                'latest_transaction' => isset($category['latest_transaction'])
                    ? Carbon::parse($category['latest_transaction'])->format('Y-m-d H:i:s')
                    : null
            ];
        }, $analytics);
    }


}
