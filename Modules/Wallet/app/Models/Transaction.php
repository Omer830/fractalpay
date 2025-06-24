<?php

namespace Modules\Wallet\Models;

use App\Helpers\ValueHelpers;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Wallet\Database\Factories\TransactionFactory;
use Modules\Wallet\DTO\TransactionDTO;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Enums\TransactionStatus;
use Modules\Wallet\Enums\TransactionType;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'transaction_id',
        'user_id',
        'receivable_type',
        'receivable_id',
        'type',
        'base_currency',
        'currency',
        'amount',
        'sender_currency',
        'sender_amount',
        'receiver_currency',
        'receiver_amount',
        'conversion_rate',
        'status',
        'is_wallet'
    ];

    public function receivable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(WalletUser::class, 'user_id');
    }

    public function scopeHasUser($query, UserDTO $userDTO)
    {
        return $query->where('user_id', $userDTO->id());
    }

    public function scopeHasReceiver($query, UserDTO $userDTO)
    {
        return $query->where('receivable_type', WalletUser::class)
                    ->where('receivable_id', $userDTO->id());
    }

    public function scopeType($query, TransactionType|string|array $type)
    {

        $types = ValueHelpers::getValues($type);

        return $query->whereIn('type', $types);
    }

    public function scopeStatus($query, TransactionStatus|string|array $status)
    {
        $status = ValueHelpers::getValues($status);

        return $query->whereIn('status', $status);
    }

    public function scopeDateRange($query, CarbonPeriod $range)
    {
        $query->whereBetween('created_at', [$range->start, $range->end]);
    }

    public function scopeAmount($query, int|array $amount)
    {
        if(is_int($amount)) {
            return $query->where('amount', $amount);
        }
        if(is_array($amount)) {
            return $query->where('amount', '>=', $amount[0])
                        ->where('amount', '<=', $amount[1]);
        }
    }

    public function scopeUser($query, int|array $user)
    {

        $query->where('receivable_type', WalletUser::class);

        if(is_int($user)) {
            return $query->where('receivable_id', $user);
        }
        if(is_array($user)) {
            return $query->whereIn('receivable_id', $user);
        }

    }

    public function scopeFilter($query, TransactionDTO $transactionDTO)
    {
        $filters = $transactionDTO->getFilters();

        $query->when($filters['type'] ?? null, function ($query, $type) {
            $query->type($type);
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->status($status);
        });

        $query->when($filters['date_range'] ?? null, function ($query, $dates) {
            $query->dateRange(CarbonPeriod::create(
                $dates[0] ?? NOW()->subDays(3),
                    $dates[1] ?? NOW()
            ));
        });

        $query->when($filters['amount'] ?? null, function ($query, $amount) {
            $query->amount($amount);
        });

        $query->when($filters['user'] ?? null, function ($query, $receivable) {
            $query->user($receivable);
        });

    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Transactions')
            ->setDescriptionForEvent(function ($event) {
                return "Transaction " . $event;
            })
            ->logOnly(['status']);
    }

    public function bill()
    {
        return $this->belongsTo(\Modules\ExpenseTracker\Models\Bill::class, 'receivable_id')
            ->where('receivable_type', 'Modules\ExpenseTracker\Models\Bill');
    }

}
