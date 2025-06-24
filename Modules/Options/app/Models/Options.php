<?php

namespace Modules\Options\Models;

use App\Helpers\HasAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Database\Factories\OptionsFactory;
use Modules\Options\Traits\handleEnums;
use Modules\Wallet\Enums\Currency;

class Options extends Model
{
    use HasFactory, handleEnums, HasAttribute;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): OptionsFactory
    {
        //return OptionsFactory::new();
    }


    public function parent()
    {
        return $this->belongsTo(self::class, 'options_id');
    }

    public function getParentNameAttribute()
    {
        return $this->parent?->name;
    }

    public function getParentSlugAttribute()
    {
        return $this->parent?->slug;
    }

    public function children()
    {
        return $this->hasMany(self::class, 'options_id');
    }
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }
    public function getCurrencyAttribute()
    {
        return $this->attribute->where('key', Attributes::CURRENCY->value)->first()?->value ?? Currency::AUD->value;
    }

    /**
     * Find a record by ID, with an optional category filter.
     *
     * @param int $id
     * @param string|null $category
     * @return Options|null
     */
    public static function findById(int $id, $category = null)
    {
        return self::findBy('id', $id, $category);
    }

    /**
     * Find a record by slug, with an optional category filter.
     *
     * @param string $slug
     * @param string|null $category
     * @return Options|null
     */
    public static function findBySlug($slug, $category = null)
    {
        return self::findBy('slug', $slug, $category);
    }

    public function scopeFindBySlug($query, $slug, $category = null)
    {
        return $query->where('slug', $slug)
            ->when($category, function($query) use ($category) {
            $query->where('category', $category);
        });
    }

    /**
     * Find a record by name, with an optional category filter.
     *
     * @param string $name
     * @param string|null $category
     * @return Options|null
     */
    public static function findByName($name, $category = null)
    {
        return self::findBy('name', $name, $category);
    }

    /**
     * Build and execute the query to find a record by a given field.
     *
     * @param string $field
     * @param mixed $value
     * @param string|null $category
     * @return Options|null
     */
    protected static function findBy($field, $value, $category = null)
    {
        $query = self::query();

        $query->where($field, $value);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->first();
    }

    public static function findAttributeByName($slug, $category, $attributeName, $defaultValue = null)
    {

        $option = self::with('attributes')
            ->findBySlug($slug, self::cleanValue($category))
            ->first();

        $attributeName = self::cleanValue($attributeName);

        return $option?->$attributeName ?? $defaultValue;

    }

    /**
     * @return int
     */
    public function getNumberOfFilesAttribute(): int
    {
        $fileAttribute = $this->attribute()->where('key', Attributes::NUMBER_OF_FILES->value)->first();
        if($fileAttribute) {
            return $fileAttribute->value;
        }
        return 1;
    }

    /**
     * @return String|Null
     */
    public function getCountryCodeAttribute(): String|Null
    {
        $fileAttribute = $this->attribute()->where('key', Attributes::COUNTRY_CODE->value)->first();
        if($fileAttribute) {
            return strtoupper($fileAttribute->value);
        }
        return null;
    }

    public static function allOptions()
    {
        return collect(config('options_config'));
    }

    public static function kycDocumentOptions()
    {
        return self::allOptions()->get(Categories::DOCUMENTS->value);
    }

}
