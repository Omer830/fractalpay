<?php

namespace Modules\PaymentMethod\Models;

use App\Helpers\HasAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PaymentMethod\Database\Factories\PaymentMethodFactory;

class PaymentMethod extends Model
{
    use HasFactory, HasAttribute;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['uuid', 'type'];

    public function payable()
    {
        return $this->morphTo();
    }
}
