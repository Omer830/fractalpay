<?php

namespace Modules\Insurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Insurance\Database\Factories\InsuranceFactory;
use Modules\Auth\Models\User;
class Insurance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "insurances";
    protected $fillable = [
        'company_name',
        'card_number',
        'policy_number',
        'terms',
        'amount',
        'user_id',
    ];

    protected static function newFactory(): InsuranceFactory
    {
        //return InsuranceFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(InsuranceUser::class);
    }

}
