<?php

namespace Modules\ExpenseTracker\Repositories;

use Modules\Auth\Models\User;
use Modules\Auth\Services\UserService;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Modules\ExpenseTracker\Models\Notification;

class VisitRepository implements \Modules\ExpenseTracker\Interfaces\VisitRepositoryInterface
{
    // Add your methods here
    public function __construct(private UserService $userService)
    {

    }

    public function create(array $data)
    {
        return Visit::create($data);
    }

    public function update(Visit $visit,array $data)
    {
        return tap($visit)->update($data);
    }

    public function delete($id)
    {

    }

    public function find($id) : Visit | Null 
    {
        return Visit::find($id);
    }

    public function getAllVisits(UserDTO $userDTO): Collection
    {
        return $userDTO->user()->visits()->get();
    }
    public function getContributorVisit(UserDTO $userDTO)
    {
        $user = $userDTO->user();
        
        $contributorVisits = Visit::whereHas('contributors', function ($query) use ($user) {
            $query->where('users.id', $user->id); 
        })->get(); 

        return  $contributorVisits->unique('id');
    }

    public function get(array $data): Collection
    {
        return Visit::whereIn("id", $data)->get();
    }

    public function findByOrganization($id) : Collection
    {
        return Visit::where('organization_id', $id)->get();
    }

    public function createNotification(array $data)
    {
        return Notification::create($data);
    }

    public function getUserNotifications(int $userId): \Illuminate\Support\Collection
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}