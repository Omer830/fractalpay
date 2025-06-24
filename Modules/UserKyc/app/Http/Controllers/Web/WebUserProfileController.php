<?php

namespace Modules\UserKyc\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Options\Services\OptionListService;
use Modules\Options\Transformers\CategoryList;
use Modules\UserKyc\Contracts\UserProfileControllerInterface;
use Modules\UserKyc\Http\Requests\UpdateUserProfileRequest;
use Modules\UserKyc\Services\UserProfileService;
use Modules\UserKyc\Http\Controllers\UserKycController;
use Modules\UserKyc\Transformers\UserKycStatus;
use Modules\UserKyc\Transformers\UserProfileResource;
use Modules\Auth\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InertiaException;
use App\Exceptions\ErrorException;

class WebUserProfileController extends UserKycController implements UserProfileControllerInterface
{
    protected $userService;

    public function __construct(
        private UserProfileService $UserProfileService,
        UserService $userService,
        OptionListService $optionListService
    ) {
        $this->userService = $userService;
        $this->optionListService = $optionListService;
    }

    public function profilePage(Request $request)
    {
        try {
            $response = [];

            $response['countries'] = $this->optionListService->getOptionsList(['categories' => 'countries']);

            if ($request->has('country')) {
                $response['states'] = $this->optionListService->getOptionsList([
                    'categories' => 'states',
                    'parent' => $request->input('country')
                ]);
            }

            if ($request->has('state')) {
                $response['cities'] = $this->optionListService->getOptionsList([
                    'categories' => 'cities',
                    'parent' => $request->input('state')
                ]);
            }

            $response['userProfile'] = new UserProfileResource($this->UserProfileService->get());

            return Inertia::render('ProfileSettings/ProfileSettings', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('ProfileSettings/ProfileSettings', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load profile page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load profile page. Please try again later.']);
        }
    }

    public function editProfilePage(Request $request)
    {
        try {
            $response = [];

            $response['countries'] = $this->optionListService->getOptionsList(['categories' => 'countries']);

            if ($request->has('country')) {
                $response['states'] = $this->optionListService->getOptionsList([
                    'categories' => 'states',
                    'parent' => $request->input('country')
                ]);
            }

            if ($request->has('state')) {
                $response['cities'] = $this->optionListService->getOptionsList([
                    'categories' => 'cities',
                    'parent' => $request->input('state')
                ]);
            }

            $response['userProfile'] = new UserProfileResource($this->UserProfileService->get());

            return Inertia::render('EditProfile/EditProfile', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('EditProfile/EditProfile', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load profile page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load profile page. Please try again later.']);
        }
    }

    public function DocTypePage(Request $request)
    {
        try {

            $response['DocumentsList'] = $this->optionListService->getOptionsList([
                'categories' => 'document_category',
            ]);
            $kycStatus = $this->getKycStatus();
            $response = [
                'kycStatus' => $kycStatus
            ];

            return Inertia::render('DocType/DocType', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('DocType/DocType', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load document type page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load document type page. Please try again later.']);
        }
    }

    public function getKycStatus()
    {
        try {
            return new UserKycStatus($this->UserProfileService->getKycStatus());
        } catch (ErrorException $e) {
            throw new InertiaException('KYC Status Retrieval', $e);
        } catch (\Exception $e) {
            Log::error('Failed to get KYC status: ', ['exception' => $e]);
            return response()->json(['error' => 'Failed to retrieve KYC status.'], 500);
        }
    }
    
    public function primaryDocumentPage(Request $request)
    {
        try {
            $kycStatus = $this->getKycStatus();
            $documents = $this->optionListService->getOptionsList([
                'categories' => 'documents',
                'parent' => 'primary-au',
                'with' => 'attributes',
            ]);
            $response = [
                'cardsData' => new CategoryList($documents),
                'kycStatus' => $kycStatus
            ];
            return Inertia::render('PrimaryDocuments/PrimaryDocuments', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('PrimaryDocuments/PrimaryDocuments', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load primary documents page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load primary documents page. Please try again later.']);
        }
    }

    public function SecondaryDocumentPage(Request $request)
    {
        try {
            $kycStatus = $this->getKycStatus();
            $documents = $this->optionListService->getOptionsList([
                'categories' => 'documents',
                'parent' => 'secondary-au',
                'with' => 'attributes',
            ]);
            $response = [
                'cardsData' => new CategoryList($documents),
                'kycStatus' => $kycStatus
            ];
            return Inertia::render('SecondaryDocuments/SecondaryDocuments', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('SecondaryDocuments/SecondaryDocuments', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load secondary documents page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load secondary documents page. Please try again later.']);
        }
    }

    public function otherDocumentPage(Request $request)
    {
        try {
            $kycStatus = $this->getKycStatus();
            $documents = $this->optionListService->getOptionsList([
                'categories' => 'documents',
                'parent' => 'other-au',
                'with' => 'attributes',
            ]);
            $response = [
                'cardsData' => new CategoryList($documents),
                'kycStatus' => $kycStatus
            ];
            return Inertia::render('OtherDocuments/OtherDocuments', $response);
        } catch (ErrorException $e) {
            throw new InertiaException('OtherDocuments/OtherDocuments', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load other documents page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load other documents page. Please try again later.']);
        }
    }

    public function documentUploadPage(Request $request)
    {
        try {
            return Inertia::render('DocumentUpload/DocumentUpload');
        } catch (ErrorException $e) {
            throw new InertiaException('DocumentUpload/DocumentUpload', $e);
        } catch (\Exception $e) {
            Log::error('Failed to load document upload page: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to load document upload page. Please try again later.']);
        }
    }

    public function getUserProfile()
    {
        try {
            return new UserProfileResource($this->UserProfileService->get());
        } catch (ErrorException $e) {
            throw new InertiaException('User Profile Retrieval', $e);
        } catch (\Exception $e) {
            Log::error('Failed to get user profile: ', ['exception' => $e]);
            return response()->json(['error' => 'Failed to retrieve user profile.'], 500);
        }
    }
    public function editProfileUpdatePage(UpdateUserProfileRequest $request)
    {
        try {
            $updatedUserProfile = $this->UserProfileService->update($request->validated());

            // Return an Inertia response
            return Inertia::render('EditProfile/EditProfile', [
                'userProfile' => $updatedUserProfile
            ])->with('success', 'Profile updated successfully!');
        } catch (ErrorException $e) {
            throw new InertiaException('EditProfile/EditProfile', $e);
        } catch (\Exception $e) {
            Log::error('Failed to update user profile: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again later.']);
        }
    }
    public function updateUserProfileData(UpdateUserProfileRequest $request)
    {
        try {
            $updatedUserProfile = $this->UserProfileService->update($request->validated());

            // Return an Inertia response
            return Inertia::render('ProfileSettings/ProfileSettings', [
                'userProfile' => $updatedUserProfile
            ])->with('success', 'Profile updated successfully!');
        } catch (ErrorException $e) {
            throw new InertiaException('ProfileSettings/ProfileSettings', $e);
        } catch (\Exception $e) {
            Log::error('Failed to update user profile: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again later.']);
        }
    }

    public function editUserProfileData(UpdateUserProfileRequest $request)
    {
        try {
            $updatedUserProfile = $this->UserProfileService->update($request->validated());

            // Return an Inertia response
            return Inertia::render('EditProfile/EditProfile', [
                'userProfile' => $updatedUserProfile
            ])->with('success', 'Profile updated successfully!');
        } catch (ErrorException $e) {
            throw new InertiaException('EditProfile/EditProfile', $e);
        } catch (\Exception $e) {
            Log::error('Failed to update user profile: ', ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again later.']);
        }
    }
    public function showWelcomePage()
    {
        return Inertia::render('WelcomePage/WelcomePage');
    }
    public function showSuccessPage()
    {
        return Inertia::render('SuccessScreen/SuccessScreen');
    }
    public function updateUserProfileImage()
    {
        // TODO: Implement updateUserProfileImage() method.
    }
}
