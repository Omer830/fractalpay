<?php

namespace Modules\Documents\Interfaces;

use Modules\ExpenseTracker\Models\Bill;
use Modules\UserKyc\DTO\UserDTO;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

interface MediaRepositoryInterface
{
    public function upload(UserDTO $userDTO, $fileName, $collectionName, array $customProperties = []) : Media;

    public function uploadBill(Bill $bill, $fileName, $collectionName = 'bill_files', array $customProperties = []): Media;
}