<?php

namespace Modules\Documents\Repositories;

use Modules\UserKyc\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Bill;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaRepository implements \Modules\Documents\Interfaces\MediaRepositoryInterface
{

    public function upload(UserDTO $userDTO, $fileName, $collectionName, $customProperties = []) : Media
    {
        $user = $userDTO->user();
        return $user?->addMediaFromRequest($fileName)
                    ->withCustomProperties($customProperties)
                    ->toMediaCollection($collectionName,env('MEDIA_DISK', 's3'));
    }

    public function uploadBill(Bill $bill, $fileName, $collectionName = 'bill_files', array $customProperties = []): Media
    {
        return $bill
            ->addMediaFromRequest($fileName)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collectionName,env('MEDIA_DISK', 's3'));
    }
}