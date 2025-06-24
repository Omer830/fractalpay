<?php

namespace Modules\ExpenseTracker\Traits;
use Modules\Options\Models\Attribute;
use Modules\ExpenseTracker\Models\Bill;
use Modules\ExpenseTracker\Models\Visit;
use \Modules\Auth\Traits\hasUserRelations as baseHasUserRelations;

trait hasUserRelations
{
    use baseHasUserRelations;

    public function visits()
    {
        return $this->hasMany(Visit::class, 'user_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'user_id');
    }

    public function ContributorBills()
    {
        return $this->belongsToMany(
            Bill::class, 
            'bill_contributor', 
            'contributor_id', 
            'bill_id' 
        );
    }
    public function ContributorVisits()
    {
        return $this->belongsToMany(
            Visit::class, 
            'visit_contributor', 
            'contributor_id', 
            'visit_id' 
        );
    }
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function createOrUpdateAttributes(array $attributes)
    {
        foreach ($attributes as $attributeData) {
           
            $attribute = $this->attributes()->where('key', $attributeData['key'])->first();
    
            if ($attribute) {
               
                $attribute->update([
                    'value' => $attributeData['value']
                ]);
            } else {
              
                $this->attributes()->create($attributeData);
            }
        }
    }

}