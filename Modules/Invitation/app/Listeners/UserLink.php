<?php

namespace Modules\Invitation\Listeners;

use Modules\Auth\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Invitation\Enums\InvitationStatus;
use Modules\Invitation\Interfaces\InvitedUserRepositoryInterface;
use Modules\Invitation\Models\UserInvitation;
use Modules\Invitation\Repositories\InvitedUserRepository;

class UserLink
{
    /**
     * Create the event listener.
     */
    public function __construct(private InvitedUserRepositoryInterface $invitedUserRepository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        if($code = $event->code) {

            $invitation = $this->invitedUserRepository->findByCode($code);

            if($invitation) {
                if($invitation->email === $user->email) {
                    $this->updateInvitation($code, $user->id);
                    return;
                }
                if($invitation->phone == $user->phone) {
                    $this->updateInvitation($code, $user->id);
                    return;
                }
            }

        }

    }

    private function updateInvitation($code, $userId)
    {
        $this->invitedUserRepository->updateInvitationByCode($code, [
            'invitation_status' => InvitationStatus::ACCEPTED,
            'invited_user'  =>  $userId
        ]);
    }
}
