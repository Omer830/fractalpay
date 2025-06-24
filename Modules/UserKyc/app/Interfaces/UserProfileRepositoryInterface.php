<?php

namespace Modules\UserKyc\Interfaces;

use Illuminate\Support\Facades\File;
use Modules\UserKyc\DTO\UserDTO;
use Modules\UserKyc\Models\UserProfile;

interface UserProfileRepositoryInterface
{

    public function getUserProfile(UserDTO $user): UserProfile;

    public function updateProfile(UserDTO $user, array $data): UserProfile;

    public function uploadProfileImage(UserDTO $user, File $file): UserProfile;

    public function getUserDocuments(int $userId);

    public function updateProfileStatus(UserDTO $user): void;
    public function updateDocumentStatus(array $response): float;
    

}