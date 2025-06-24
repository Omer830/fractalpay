<?php

namespace Modules\Invitation\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Auth\Enums\CommonKeys;
use Modules\Invitation\Http\Requests\OpenInvite;
use Modules\Invitation\Http\Requests\SendInvitation;
use Modules\Invitation\Models\UserInvitation;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;

interface InvitedUserControllerInterface
{

    public function sendInviteByEmail(SendInvitation $request);

    public function openInvitation(OpenInvite $request);

    public function getInvitationList(Request $request);

    public function getConnectedUsers(Request $request) : AnonymousResourceCollection;

    public function acceptInvitation(OpenInvite $request, ?UserInvitation $invitation);

    public function rejectInvitation(OpenInvite $request, ?UserInvitation $invitation);
}