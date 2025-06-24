<?php

namespace Modules\ExpenseTracker\Models;

use Modules\Auth\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bill extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;

    protected $table = 'bills';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'visit_id',
        'category',
        'user_id',
        'amount',
        'paid',
        'balance',
        'description',
        'invoice_number',
        'due_date',
        'already_paid'
    ];

    public function user()
    {
        return $this->belongsTo(ExpenseUser::class, 'user_id');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function contributors()
    {
        return $this->belongsToMany(User::class, 'bill_contributor', 'bill_id', 'contributor_id')
                    ->using(BillContributor::class);
    }

    // Getter for 'paid' attribute
    public function getPaidAttribute($value)
    {
        return max(0, $value); // Ensure the paid amount is never less than 0
    }

    // Setter for 'amount' attribute and updating 'balance'
    public function setAmountAttribute($value)
    {
        // Ensure the 'amount' attribute is non-negative
        $this->attributes['amount'] = max(0, $value);

        // Recalculate balance based on new amount and existing paid amount
        $this->recalculateBalance();
    }

    // Setter for 'paid' attribute and updating 'balance'
    public function setPaidAttribute($value)
    {
        // Ensure the 'paid' attribute is non-negative
        $this->attributes['paid'] = max(0, $value);

        // Recalculate balance based on new paid amount and existing amount
        $this->recalculateBalance();
    }

    // Helper method to recalculate the balance and set the 'already_paid' flag
    protected function recalculateBalance()
    {
        $amount = $this->attributes['amount'] ?? 0; // Fallback to 0 if amount is not set
        $paid = $this->attributes['paid'] ?? 0; // Fallback to 0 if paid is not set

        // Calculate the balance
        $balance = max(0, $amount - $paid);
        $this->attributes['balance'] = $balance;

        // Set the 'already_paid' flag if the balance is fully paid
        $this->attributes['already_paid'] = $balance <= 0;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bill_files')->singleFile();
    }

    public function getBillFileUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('bill_files');

        if (!$media) {
            return null;
        }

        
        if ($media->disk === 's3') {
            return Storage::disk('s3')->temporaryUrl(
                $media->getPath(), now()->addMinutes(15)
            );
        }

    
        return $media->getUrl();
    }



}
