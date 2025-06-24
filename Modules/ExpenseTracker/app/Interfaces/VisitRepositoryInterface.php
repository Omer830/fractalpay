<?php

namespace Modules\ExpenseTracker\Interfaces;
use Modules\Auth\Models\User;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Database\Eloquent\Collection;

interface VisitRepositoryInterface
{
    public function create(array $data);

    public function update(Visit $visit, array $data);

    public function delete($id);

    public function find($id) : Visit | Null;

    public function get(array $data): Collection;

    public function getAllVisits(UserDTO $user) : Collection;

    public function findByOrganization($id) : Collection;

    public function getContributorVisit(UserDTO $userDTO);

    public function createNotification(array $data);
    
    public function getUserNotifications(int $userId): \Illuminate\Support\Collection;


}