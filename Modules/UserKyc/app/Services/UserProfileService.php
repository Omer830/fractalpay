<?php

namespace Modules\UserKyc\Services;

use Modules\UserKyc\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Modules\Options\Models\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Options\Transformers\CategoryList;
use Modules\Options\Services\OptionListService;
use Modules\UserKyc\Interfaces\UserProfileRepositoryInterface;

class UserProfileService
{
    private OptionListService $optionListService;
    public function __construct(private UserProfileRepositoryInterface $UserProfileRepository,OptionListService $optionListService)
    {
        $this->optionListService = $optionListService;
        $this->userDto = new UserDTO(Auth::user());
    }

    public function get()
    {
        try {

            $user = Auth::user();
            $userDTO = new UserDTO($user);
            $this->setUserStatus();
            return $this->UserProfileRepository->getUserProfile($userDTO);

        }
        catch (\Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }
    }

    public function update(array $data)
    {
        try {
            $user = Auth::user();
            $userDTO = new UserDTO($user);
            $this->setUserStatus();
            return $this->UserProfileRepository->updateProfile($userDTO, $data);
        }
        catch (\Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }
    }
    public function setUserStatus(){
        $user = Auth::user();
        $userDTO = new UserDTO($user);
        $this->UserProfileRepository->updateProfileStatus($userDTO);
    }
    public function setUserDocumentsStatus(){
        $kycStatus=$this->getKycStatus();
        $completitionPercentage=$this->UserProfileRepository->updateDocumentStatus($kycStatus);
        $completitionPercentage=round($completitionPercentage,2);
        
        // Save session data for Proof of Identity status
        Session::put('proofOfIdentityStatus', [
            'completion_percentage' => $completitionPercentage,
            'statusMessage' =>$kycStatus['statusMessage'],
        ]);
    }

    public function getKycStatus()
    {
        try {
            // Define required points for KYC completion
            $requiredPoints = 100;

            // Retrieve document options
            $documentOptions = collect(Options::kycDocumentOptions());
            
            // Validate document options structure
            if ($documentOptions->isEmpty()) {
                throw new \Exception("Document options not configured correctly.");
            }

            // Calculate total points based on uploaded documents
            $totalPoints = 0;
            $uploadedDocuments = $this->getUploadedDocuments($documentOptions, $totalPoints);
            // Calculate remaining points needed
            $remainingPoints = max(0, $requiredPoints - $totalPoints);

            // Determine KYC status message
            $statusMessage = $this->determineKycStatusMessage($totalPoints, $requiredPoints);

            // Prepare response data
            return [
                'uploadedDocuments' => $uploadedDocuments,
                'totalPoints'       => $totalPoints,
                'remainingPoints'   => $remainingPoints,
                'statusMessage'     => $statusMessage,
            ];
        } catch (\Exception $e) {
            // Handle exceptions and provide meaningful error messages
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    private function getUploadedDocuments($documentOptions, &$totalPoints)
    {
        // Get the user once
        $user = $this->userDto->user();
        // Group media by collection name to optimize lookup
        $mediaCollections = $user->media->groupBy('collection_name');
        // dd($documentOptions);

        // Filter documents and calculate total points
        return $documentOptions->filter(function ($document) use ($mediaCollections, &$totalPoints) {
            if (isset($document['slug'], $document['points'], $document['sides_required'])) {
                // Check if document slug is in uploaded types
                if (in_array($document['slug'], $mediaCollections->keys()->toArray())) {
                    $uploadedCount = $mediaCollections[$document['slug']]->count();

                    // Check if uploaded count meets or exceeds required sides
                    if ($uploadedCount >= $document['sides_required']) {
                        $totalPoints += $document['points'];
                        return true;
                    }
                }
            }
            return false;
        })->pluck('slug')->values()->all();
    }


    private function determineKycStatusMessage($totalPoints, $requiredPoints)
    {
        if ($totalPoints >= $requiredPoints) {
            return "KYC completed.";
        } elseif ($totalPoints > 0) {
            return "KYC incomplete, more documents needed.";
        } else {
            return "No documents uploaded.";
        }
    }

      public function getUserDocuments()
    {
        return $this->UserProfileRepository->getUserDocuments( $this->userDto->id);
    }



}