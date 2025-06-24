<?php

namespace Modules\Invitation\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Modules\Auth\Repositories\HelperRepository;
use Modules\Invitation\DTO\UserDTO;
use Modules\Invitation\Models\UserInvitation;

interface InvitedUserRepositoryInterface
{

    public function findByCode(string $code) : ?UserInvitation;

    public function findById(int $id) : ?UserInvitation;

    public function findByUserId(UserDTO $user, int $userId) : ?UserInvitation;

    public function getUsers(array $emails): Collection;

    public function createInvitationForExistingUser(UserDTO $Inviter, UserDTO $Invited) : UserInvitation;

    public function createInvitationForNewUser(UserDTO $Inviter, array $data) : UserInvitation;

    public function saveInvitation($find, $data) : UserInvitation;

    public function updateInvitationStatusByCode($code, $status) : Bool|null;

    public function updateInvitationStatusById(int $id, $status) : Bool|null;

    public function updateInvitationByCode($code, $values) : Bool|null;

    public function updateInvitation(UserInvitation $invitation, array $values) : Bool|null;

    public function getInvitations(UserDTO $user, array $filters) : Collection;

    public function getUsersByStatus(UserDTO $user, array $statuses) : HasMany;

    public function updateInvitationById(int $id, $values) : Bool|null;

    public function deleteInvitation(int|UserInvitation $invitation) : Bool|null;
    
    public function getAcceptedUsers(UserDTO $user): Collection;

     public function getPendingUsers(UserDTO $user): Collection;

}