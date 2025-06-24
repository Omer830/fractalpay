<?php

namespace Modules\Auth\Traits;

use Illuminate\Support\Str;
use Modules\Insurance\Models\Insurance;
use Modules\Invitation\Models\InvitedUser;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Attribute;
use Modules\Options\Models\Options;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait hasUserRelations {

    public function country()
    {
        return $this->belongsTo(Options::class, 'country_id');
    }

    public function getCountryNameAttribute()
    {
        return $this->country?->name;
    }

    public function getCountryCodeAttribute()
    {
        return Options::findAttributeByName($this->country?->slug, Categories::COUNTRIES, Attributes::COUNTRY_CODE);
    }

    public function insurance()
    {
        return $this->hasOne(Insurance::class, 'user_id');
    }

    /** ATTRIBUTES */

    /** User & Profile */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getInitialsAttribute(): string
    {
        $firstNameInitial = Str::substr($this->first_name, 0, 1);
        $lastNameInitial = Str::substr($this->last_name, 0, 1);

        return Str::upper($firstNameInitial . $lastNameInitial);
    }

    /** Insurance */
    public function getHasInsuranceAttribute(): bool
    {
        return (bool) $this->insurance;
    }


    public function getProfileImageAttribute(): ?string
    {
        $media = $this->getFirstMedia('profile-image');
        return $media ? $media->getUrl() : null;
    }

    public function avatar()
    {
        return $this->hasOne(Media::class, 'model_id', 'id')
                    ->where('model_type', 'Modules\UserKyc\Models\UserProfile')
                    ->where('collection_name', 'profile-image');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? $this->avatar->getUrl() : null;
    }

    //Todo: need to rename this to feature instead
    //Update table, model and all the references to feature
    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }


}