<?php

namespace Modules\Wallet\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentMethod\Models\PaymentMethod;
use Modules\Wallet\Database\Factories\ScheduleFactory;
use Modules\Wallet\Enums\TransactionFrequency;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'currency',
        'amount',
        'exchange_rate',
        'wallet_id',
        'frequency',
        'start_date',
        'next_run_date',
        'end_date',
        'receivable_type',
        'receivable_id',
        'payment_method_id',
        'description',
        'schedule_status',
        'is_wallet'
    ];

    public function sender()
    {
        return $this->belongsTo(WalletUser::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(WalletUser::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(WalletUser::class, 'receivable_id');
    }

    public function receivable()
    {
        return $this->morphTo();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function updateNextRun()
    {
        $this->update(['next_run_date' => $this->getNextRunDate()]);
    }

    public function getNextRunDate()
    {
        $currentNextRun = $this->next_run_date;

        if($currentNextRun) {

            $date = Carbon::make($currentNextRun);

            $frequency = $this->frequency;
            if($frequency === TransactionFrequency::ONE_OFF->value) {
                return null;
            }

            if($frequency === TransactionFrequency::WEEKLY->value) {
                return $date->addWeek();
            }

            if($frequency === TransactionFrequency::MONTHLY->value) {
                return $date->addMonth();
            }

            if($frequency === TransactionFrequency::FORTNIGHTLY->value) {
                return $date->addWeeks(2);
            }

            if($frequency === TransactionFrequency::QUARTERLY->value) {
                return $date->addMonths(3);
            }

            if($frequency === TransactionFrequency::YEARLY->value) {
                return $date->addYear();
            }

        }

    }
}
