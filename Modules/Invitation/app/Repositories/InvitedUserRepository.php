<?php

namespace Modules\Invitation\Repositories;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Modules\Auth\Repositories\HelperRepository;
use Modules\Invitation\DTO\UserDTO;
use Modules\Invitation\Enums\InvitationStatus;
use Modules\Invitation\Models\UserInvitation;

class InvitedUserRepository implements \Modules\Invitation\Interfaces\InvitedUserRepositoryInterface
{

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function findByCode(string $code) : ?UserInvitation
    {
        return UserInvitation::findByCode($code)->first();
    }

    public function findById(int $id) : ?UserInvitation
    {
        return UserInvitation::find($id);
    }

    public function findByUserId(UserDTO $user, int $userId) : ?UserInvitation
    {
        return $user->user()->userInvitations()
                    ->where('invited_user', $userId)
                    ->first();
    }

    public function getUsers(array $emails): Collection
    {
        return $this->userRepository->findByEmail($emails);
    }

    public function createInvitationForExistingUser(UserDTO $Inviter, UserDTO $Invited) : UserInvitation
    {
        $data = [
            'invitation_code'   =>  HelperRepository::generateInvitationCode(4) . $Inviter->id(),
            'email'             =>  $Invited->email ?? null,
            'phone_number'      =>  $Invited->phone_number ?? null
        ];

        return $this->saveInvitation(['user_id' => $Inviter->id(), 'invited_user' => $Invited->id(), 'name' => $Invited->name() ], $data);
    }

    public function createInvitationForNewUser(UserDTO $Inviter, array $userData) : UserInvitation
    {

        $data = [
            'invitation_code'   =>  HelperRepository::generateInvitationCode(4) . $Inviter->id(),
            'name'              =>  $userData['name'] ?? null,
            'phone_number'      =>  $userData['phone_number'] ?? null
        ];

        return $this->saveInvitation(
            ['user_id' => $Inviter->id(), 'email' => $userData['email'] ?? null ],
            $data
        );

    }

    public function saveInvitation($find, $data) : UserInvitation
    {
        return UserInvitation::updateOrCreate($find, $data);
    }

    public function getInvitations(UserDTO $user, array $filters) : Collection
    {
        $query = $user->user()?->userInvitations();

        foreach($filters as $column => $value) {
            $query->where($column, $value);
        }

        return $query->with('user')->get();
    }

    public function getAcceptedUsers(UserDTO $user): Collection
    {
        
       $received = $this->getUsersByStatus($user, [InvitationStatus::ACCEPTED])
        ->with('user.avatar')
        ->get();

        $sent = $this->getInvitedUserByStatus($user, [InvitationStatus::ACCEPTED])
            ->with('inviter.avatar')
            ->get();

        return $received->merge($sent);
    }

    public function getPendingUsers(UserDTO $user): Collection
    {
        
        $received = $this->getUsersByStatus($user, [InvitationStatus::PENDING])
        ->with('user.avatar')
        ->get();

    $sent = $this->getInvitedUserByStatus($user, [InvitationStatus::PENDING])
        ->with('inviter.avatar')
        ->get();

    return $received->merge($sent);
    

    }


    public function getInvitedUserByStatus(UserDTO $user, array $statuses)
    {
        return UserInvitation::where(function ($query) use ($user, $statuses) {
            $query->where('invited_user', $user->user()->id)
            ->whereIn('invitation_status', $statuses);
        });
    }

    public function getUsersByStatus(UserDTO $user, array $statuses) : HasMany
    {
        return $user->user()?->userInvitations()->withStatus($statuses);
    }

    public function updateInvitationStatusByCode($code, $status) : Bool|null
    {
        return $this->findByCode($code)?->update(['invitation_status' => $status]);
    }

    public function updateInvitationStatusById($id, $status) : Bool|null
    {
        return $this->findById($id)?->update(['invitation_status' => $status]);
    }

    public function updateInvitationByCode($code, $values = []) : Bool|null
    {
        return $this->findByCode($code)?->update($values);
    }

    public function updateInvitation(UserInvitation $invitation, $values = []) : Bool|null
    {
        return $invitation->update($values);
    }

    public function updateInvitationById($id, $values = []) : Bool|null
    {
        return $this->findById($id)?->update($values);
    }

    public function deleteInvitation(int|UserInvitation $invitation): ?bool
    {
        $invitation = is_int($invitation) ? $this->findById($invitation) : $invitation;

        return $invitation?->delete();
    }


}