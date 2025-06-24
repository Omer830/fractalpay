<?php

namespace Modules\UserKyc\Http\Controllers\API;

use Inertia\Inertia;
use Modules\Auth\Services\UserService;
use Modules\Options\Services\OptionListService;
use Modules\UserKyc\Transformers\UserKycStatus;
use Modules\UserKyc\Services\UserProfileService;
use Modules\UserKyc\Transformers\UserProfileResource;
use Modules\UserKyc\Transformers\UserDocumentResource;
use Modules\UserKyc\Http\Controllers\UserKycController;
use Modules\UserKyc\Http\Requests\UpdateUserProfileRequest;
use Modules\UserKyc\Contracts\UserProfileControllerInterface;

class APIUserProfileController extends UserKycController implements UserProfileControllerInterface
{
    protected $userService;
    public function __construct(private UserProfileService $UserProfileService,UserService $userService,OptionListService $optionListService)
    {
        $this->userService = $userService;
        $this->optionListService = $optionListService;
    }

    public function getUserProfile()
    {
        return new UserProfileResource($this->UserProfileService->get());
    }

    public function updateUserProfileData(UpdateUserProfileRequest $request)
    {
        return new UserProfileResource($this->UserProfileService->update($request->validated()));
    }

    public function updateUserProfileImage()
    {
        // TODO: Implement updateUserProfileImage() method.
    }

    public function getKycStatus()
    {
        return new UserKycStatus($this->UserProfileService->getKycStatus());
    }

    public function getUserDocuments()
    {

        $documents = $this->UserProfileService->getUserDocuments();

        return UserDocumentResource::collection($documents);
    }



}