<?php

namespace Modules\UserKyc\Repositories;

use Illuminate\Support\Facades\Session;
use Modules\UserKyc\Models\UserProfile;
use Modules\Options\Enums\Attributes;
use Modules\Options\Transformers\CategoryList;

class UserProfileRepository implements \Modules\UserKyc\Interfaces\UserProfileRepositoryInterface
{

    public function __construct(private UserProfile $model)
    {
    }

    public function getUserProfile($user): UserProfile
    {
        return $this->model->findOrFail($user->id);
    }

    public function updateProfile($user, array $data): UserProfile
    {
        $userProfile = $this->getUserProfile($user);
        $userProfile->update($data);
        return $userProfile;
    }

    public function uploadProfileImage($file, $user): UserProfile
    {
        //Todo: implement
    }

    public function getUserDocuments(int $userId)
    {
        $user = $this->model->findOrFail($userId);
        return $user->getAllDocuments();
    }
    public function updateProfileStatus($user): void
    {
     
        $userProfile = $this->getUserProfile($user);
        $profileData = [
            Attributes::PROFILE_NAME->value => $userProfile->first_name,
            Attributes::PROFILE_ADDRESS->value => $userProfile->address,
            Attributes::PROFILE_POSTAL_CODE->value => $userProfile->postal_code,
            Attributes::PROFILE_COUNTRY->value => $userProfile->country_id,
            Attributes::PROFILE_STATE->value => $userProfile->state,
            Attributes::PROFILE_GENDER->value => $userProfile->gender,
            Attributes::PROFILE_DOB->value => $userProfile->date_of_birth,
            Attributes::PROFILE_USERNAME->value => $userProfile->user_name,
        ];
        $totalAttributes = count($profileData);
        $filledAttributes = count(array_filter($profileData));

        $completionPercentage = ($filledAttributes / $totalAttributes) * 100;
        Session::put('profile_status', [
            'completion_percentage' => $completionPercentage,
            'profile_data' => $profileData
        ]);
    }
    public function updateDocumentStatus($kycStatus): float
{
    $completionPercentage = ($kycStatus['totalPoints'] / 100) * 100;
    return $completionPercentage;
       
   
}
}