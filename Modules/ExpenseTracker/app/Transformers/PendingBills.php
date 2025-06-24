<?php

namespace Modules\ExpenseTracker\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Common\TimestampResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingBills extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'amount' => (float)$this->amount,
            'category' => $this->category,
            'invoice_number' => $this->invoice_number,
            'due_date' => $this->due_date ? Carbon::parse($this->due_date)->format('Y-m-d') : null,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'balance' => (float)$this->balance,
            'is_paid' => $this->already_paid
        ];
    }
}
