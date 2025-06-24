<?php

namespace Modules\UserKyc\Contracts;

use Modules\UserKyc\Http\Requests\UpdateUserProfileRequest;

interface UserProfileControllerInterface
{

    public function getUserProfile();

    public function updateUserProfileData(UpdateUserProfileRequest $request);

    public function updateUserProfileImage();

}