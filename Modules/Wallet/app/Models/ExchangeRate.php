<?php

namespace Modules\Wallet\Models;

use App\Helpers\ValueHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Database\Factories\ExchangeRateFactory;
use Modules\Wallet\Enums\Currency;

class ExchangeRate extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'base',
        'currency',
        'rate',
        'timestamp'
    ];

    protected static function newFactory(): ExchangeRateFactory
    {
        //return ExchangeRateFactory::new();
    }

    public static function findByName(string|Currency|array $name, Currency|string $baseName = null)
    {
        $names = ValueHelpers::getValues($name);
        $query = self::query();

        $query->whereIn(DB::raw('LOWER(currency)'), $names);

        if($baseName) {
            $baseName = ValueHelpers::getEnumValue($baseName);
            $query->where(DB::raw('LOWER(base)'), $baseName);
        }

        if(count($names) === 1) {
            return $query->first()?->rate;
        }

        return $query->get()->pluck('rate', 'currency');

    }
}
