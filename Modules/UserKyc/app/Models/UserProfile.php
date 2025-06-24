<?php

namespace Modules\UserKyc\Models;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Modules\Options\Models\Options;
use Modules\Options\Enums\Attributes;
use Modules\Options\Enums\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
//use Modules\UserKyc\Database\Factories\UserProfileFactory;

class UserProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_name',
        'address',
        'state',
        'city',
        'date_of_birth',
        'gender',
        'country_id',
        'postal_code'
    ];

    protected $appends = ['profile_image'];

//    protected static function newFactory(): UserProfileFactory
//    {
//        //return UserProfileFactory::new();
//    }
    // Definining Accessor for Date format of dob
    public function getDateOfBirthAttribute($value)
    {
        
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('d-m-Y');
        }
        return null; 
    }
    public function setDateOfBirthAttribute($value)
    {
        if ($value) {
            if ($value) {
                $this->attributes['date_of_birth'] = \Carbon\Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
            }
        }
    }
    public function registerMediaCollections(): void
    {

        if( ($collectionName = request()->file_type) && request()->hasFile('file')) {

            $this->setFileNumbers($collectionName);
            return;
        }

        foreach(request()->file() as $documentName => $file)
        {

            $collectionName = request()->file($documentName);
            if($collectionName) {
                $this->setFileNumbers($collectionName, $documentName);
            }

        }


    }

    public function setFileNumbers($collectionName, $documentName = null, $category = Categories::DOCUMENTS->value)
    {

        $documentName = $documentName ?? $collectionName;

        $keep = Options::findAttributeByName($documentName, $category, 'number_of_files', 10);

        $this->addMediaCollection($collectionName)->onlyKeepLatest($keep);

    }

    public function getProfileImageAttribute(): ?string
    {
        $media = $this->getFirstMedia('profile-image');

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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('profile_image')
            ->fit(Fit::Contain, 600, 600)
            ->keepOriginalImageFormat()
            ->nonQueued();

    }
    public function getCityAttribute($value): string
    {
        return ucfirst($value);
    }

    public function getAllDocuments()
    {
         return $this->media()
        ->get();
    }

}
