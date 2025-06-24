<?php

namespace Modules\Documents\Services;

use AllowDynamicProperties;
use Modules\Auth\Models\User;
use Modules\UserKyc\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Modules\Documents\Models\Media;
use Modules\Options\Models\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Support\Facades\Notification;
use Modules\UserKyc\Notifications\uploadProfile;
use Modules\Documents\Http\Requests\UserKycDocuments;
use Modules\Documents\Interfaces\MediaRepositoryInterface;

#[AllowDynamicProperties] class MediaService
{

    public function __construct(private MediaRepositoryInterface $MediaRepository)
    {
        $this->userDto = new UserDTO(Auth::user());
    }

    public function uploadFile($data, $fileName = 'file')
    {

        $customProperties = $this->getCustomProperties($data, [$fileName, 'file_type']);

        $collectionName = $data['file_type'] ?? 'other';

        $this->handleFileNumber($collectionName, $fileName, $customProperties);

        if(ISSET($data[$fileName])) {
            return $this->MediaRepository->upload($this->userDto, $fileName, $collectionName, $customProperties);
        }

    }
    
    public function uploadBill(Bill $bill, $fileName, array $customProperties = [])
    {
        return $this->MediaRepository->uploadBill($bill, $fileName, 'bill_files', $customProperties);
    }

    private function handleFileNumber($collectionName, $fileName, $customProperties)
    {

        $option = Options::findBySlug($collectionName);

        if($option->number_of_files > 1) {

            $existingFile = $this->userDto->user()->getMedia($fileName, function ($media) use($customProperties) {
                if(ISSET($customProperties['side']) && ISSET($media->custom_properties['side']))
                {
                    return $customProperties['side'] === $media->custom_properties['side'];
                }
                return false;
            })->first();

            $this->deleteFile($existingFile, $this->userDto->user()->id);

        }
    }

    private function getCustomProperties($data, $except)
    {
        $except += []; //Add any other files you want to remove from data
        return collect($data)->except($except)->toArray();
    }

    public function getFiles($fileType)
    {

        if(!$fileType) {
            throw new ErrorException('INVALID_FILE_TYPE');
        }
      
        $mediaItems = $this->userDto->user()->getMedia($fileType);

        // Map over the media items to get their URLs
         return $mediaItems->map(function ($mediaItem) {
        $url = $mediaItem->disk === 's3'
            ? Storage::disk('s3')->temporaryUrl(
                $mediaItem->getPathRelativeToRoot(),
                now()->addMinutes(15)
            )
            : $mediaItem->getUrl();

        return [
            'id' => $mediaItem->id,
            'url' => $url,
            'name' => $mediaItem->name,
            'size' => $mediaItem->size,
            'custom_properties' => $mediaItem->custom_properties,
            'created_at' => $mediaItem->created_at,
            'updated_at' => $mediaItem->updated_at,
        ];
    });

    }

    public function deleteFile($file, int|null $id = null)
    {
        $id = $id ?? $this->userDto->user()->id;

        if ($file && $file->model_id === $id) {
            return (bool) $file->delete();
        }
        return false;
    }

}