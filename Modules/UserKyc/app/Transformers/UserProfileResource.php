<?php

namespace Modules\UserKyc\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\UserKyc\Transformers\UserDocumentResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name'                     =>  $this->user_name,
            'first_name'                    =>  $this->first_name,
            'last_name'                     =>  $this->last_name,
            'email'                         =>  $this->email,
            'secondary_email'               =>  $this->secondary_email ?? '',
            'secondary_email_verified'      =>  $this->secondary_email_verified_at !== null,
            'phone_number'                  =>  $this->phone_number,
            'address'                       =>  $this->address,
            'country'                       =>  $this->country_id,
            'state'                         =>  $this->state,
            'city'                          =>  $this->city,
            'gender'                        =>  $this->gender,
            'postal_code'                   =>  $this->postal_code,
            'date_of_birth'                 =>  $this->date_of_birth,
            'profile_image'                 =>  $this->profile_image,
            'userDocument'                  =>  UserDocumentResource::collection($this->getAllDocuments()),
        ];
    }
}
