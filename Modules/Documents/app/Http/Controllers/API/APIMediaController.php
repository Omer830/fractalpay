<?php

namespace Modules\Documents\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Documents\Contracts\MediaControllerInterface;
use Modules\Documents\Http\Requests\OtherDocuments;
use Modules\Documents\Http\Requests\UserKycDocuments;
use Modules\Documents\Http\Requests\ProfileImage;
use Modules\Documents\Http\Requests\SecondaryDocuments;
use Modules\Documents\Models\Media;
use Modules\Documents\Services\MediaService;
use Modules\Documents\Http\Controllers\DocumentsController;
use Modules\Documents\Transformers\Files;
use Modules\Documents\Transformers\ProfileImageResource;
use Modules\Documents\Transformers\UserKycUploadResource;


class APIMediaController extends DocumentsController implements MediaControllerInterface
{

    public function __construct(private MediaService $MediaService)
    {
        
    }

    public function uploadProfileImage(ProfileImage $request)
    {
        return new ProfileImageResource($this->MediaService->uploadFile($request->validated()));
    }

    public function uploadKyc(UserKycDocuments $request)
    {
        return new UserKycUploadResource($this->MediaService->uploadFile($request->validated()));
    }

    public function getDocuments(Request $request)
    {
        return Files::collection($this->MediaService->getFiles($request->file_type));
    }

    public function deleteDocuments(Media $media)
    {
        return $this->MediaService->deleteFile($media);
    }


}