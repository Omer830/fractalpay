<?php

namespace Modules\Insurance\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Modules\Insurance\DTO\UserDTO;
use Modules\Insurance\Models\Insurance;

interface InsuranceRepositoryInterface
{
    // Add your methods here

    public function get(UserDTO $userDTO): ?Insurance;

    public function create(UserDTO $userDTO, array $data) : Insurance|Model;

    public function delete(UserDTO $userDTO): bool;
}