<?php

namespace Modules\Insurance\Repositories;
use Modules\Insurance\DTO\UserDTO;
use Modules\Insurance\Models\Insurance;
class InsuranceRepository implements \Modules\Insurance\Interfaces\InsuranceRepositoryInterface
{
    // Add your methods here
    public function get(UserDTO $userDTO): ?Insurance
    {
        $insurances = $userDTO->user?->insurance()->get();
        return $insurances->last() ?? null;
    }
    public function create(UserDTO $userDTO, array $data) : Insurance
    {
        return $userDTO->user?->insurance()->create($data);
    }

    public function delete(UserDTO $userDTO) : bool
    {
        return $userDTO->user?->insurance()->delete();
    }
}