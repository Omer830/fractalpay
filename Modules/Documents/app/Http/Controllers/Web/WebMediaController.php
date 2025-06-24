<?php


namespace Modules\Documents\Http\Controllers\Web;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Documents\Models\Media;
use Illuminate\Support\Facades\Auth;
use Modules\Documents\Transformers\Files;
use Illuminate\Support\Facades\Notification;
use Modules\Documents\Services\MediaService;
use Modules\UserKyc\Notifications\uploadProfile;
use Modules\UserKyc\Services\UserProfileService;
use Modules\Documents\Http\Requests\ProfileImage;
use Modules\Documents\Http\Requests\OtherDocuments;
use Modules\Documents\Http\Requests\UserKycDocuments;
use Modules\Documents\Http\Requests\SecondaryDocuments;
use Modules\Documents\Transformers\ProfileImageResource;
use Modules\Documents\Contracts\MediaControllerInterface;
use Modules\Documents\Transformers\UserKycUploadResource;
use Modules\Documents\Http\Controllers\DocumentsController;


class WebMediaController extends DocumentsController implements MediaControllerInterface
{

    public function __construct(private MediaService $MediaService,private UserProfileService $UserProfileService)
    {

    }

    public function uploadProfileImage(ProfileImage $request)
    {
        $uploadedFile = $this->MediaService->uploadFile($request->validated());

        if($uploadedFile){
            $user = Auth::user(); 
            Notification::send($user, new uploadProfile());
        }
        $resource = new ProfileImageResource($uploadedFile);
        session(['profilePic' => $this->UserProfileService->get()->profile_image]);
        return Inertia::render('ProfileSettings', [
            'profileImage' => $resource->toArray($request)
        ]);
    }

    public function uploadProfileImageEdit(ProfileImage $request)
    {
        $uploadedFile = $this->MediaService->uploadFile($request->validated());

        if($uploadedFile){
            $user = Auth::user();
            Notification::send($user, new uploadProfile());
        }
        $resource = new ProfileImageResource($uploadedFile);

        return Inertia::render('EditProfile', [
            'profileImage' => $resource->toArray($request)
        ]);
    }

    public function uploadKyc(UserKycDocuments $request)
    {
        try {
            $uploadedFile = $this->MediaService->uploadFile($request->validated());
            $resource = new UserKycUploadResource($uploadedFile);
            $documents = $this->MediaService->getFiles($request->file_type);

            return redirect()->back()->with('flash_response', [
                'message' => 'KYC document uploaded successfully.',
                'kycDetails' => $resource->toArray($request),
                'documents' => $documents,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to upload KYC document for user {$request->user()->id}: " . $e->getMessage());

            return redirect()->back()->with('flash_response', [
                'message' => 'Failed to upload KYC document. Please try again.',
                'kycDetails' => [],
                'documents' => [],
                'success' => false
            ]);
        }
    }



    public function documentUploadPage(Request $request)
    {
        $files = $this->MediaService->getFiles($request->file_type);
        $filesResource = Files::collection($files);

        return Inertia::render('DocumentUpload/DocumentUpload', [
            'documents' => $filesResource->resolve($request)
        ]) ;
    }
    public function getDocuments(Request $request)
    {
        return Files::collection($this->MediaService->getFiles($request->file_type));
    }

    public function deleteDocuments(Media $media)
    {
        try {

            $result = $this->MediaService->deleteFile($media);

            $message = $result ? 'Document successfully deleted.' : 'Failed to delete the document.';
            request()->session()->flash('message', $message);

            request()->session()->flash('success', $result);

            return redirect()->back();
        } catch (\Exception $e) {

            Log::error("Error deleting document: " . $e->getMessage());

            request()->session()->flash('error', 'An error occurred while deleting the document.');

            return redirect()->back();
        }
    }

}