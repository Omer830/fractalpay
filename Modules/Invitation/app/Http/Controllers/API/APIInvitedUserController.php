<?php

namespace Modules\Invitation\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Auth\Enums\CommonKeys;
use Modules\Invitation\Models\UserInvitation;
use Modules\Invitation\Transformers\GetInvites;
use Modules\Invitation\Http\Requests\OpenInvite;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Invitation\Http\Requests\SendInvitation;
use Modules\Invitation\Http\Requests\RemoveConnectedUser;
use Modules\Invitation\Http\Controllers\InvitationController;
use Modules\Invitation\Contracts\InvitedUserControllerInterface;

class APIInvitedUserController extends InvitationController implements InvitedUserControllerInterface
{

    public function __construct(private InvitedUserService $InvitedUserService)
    {

    }

    public function sendInviteByEmail(SendInvitation $request)
    {
        return $this->InvitedUserService->processEmailInvitation($request->validated());
    }

    public function openInvitation(OpenInvite $request)
    {
        return $this->InvitedUserService->invitationOpened($request->validated());
    }

    public function getInvitationList(Request $request)
    {
        return GetInvites::collection($this->InvitedUserService->userInvitations($request->all()));
    }

    public function getConnectedUsers(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ConnectedUsers::collection($this->InvitedUserService->getUserList());
    }
    public function getPendingUser(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        
        return ConnectedUsers::collection($this->InvitedUserService->getPendingUserList());
    }

    public function acceptInvitation(OpenInvite $request, ?UserInvitation $invitation)
    {
        return $this->InvitedUserService->invitationAccepted($request->validated(), $invitation);
    }

    public function rejectInvitation(OpenInvite $request, ?UserInvitation $invitation)
    {
        return $this->InvitedUserService->invitationRejected($request->validated(), $invitation);
    }

    public function removeConnectedUsers(RemoveConnectedUser $request)
    {
        return $this->InvitedUserService->removeUser($request->validated());
    }

}