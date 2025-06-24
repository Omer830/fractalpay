<?php

namespace Modules\Documents\Contracts;

use Modules\Documents\Http\Requests\UserKycDocuments;
use Modules\Documents\Http\Requests\ProfileImage;
use Illuminate\Http\Request;
use Modules\Documents\Models\Media;

interface MediaControllerInterface
{
    public function uploadProfileImage(ProfileImage $request);

    public function uploadKyc(UserKycDocuments $request);

    public function getDocuments(Request $request);

    public function deleteDocuments(Media $media);

}