<?php

namespace Modules\Invitation\Http\Controllers\Web;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Invitation\Http\Requests\RemoveConnectedUser;
use Twilio\TwiML\Voice\Redirect;
use Modules\Auth\Enums\CommonKeys;
use App\Exceptions\InertiaException;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\InertiaCognito;
use Modules\Invitation\Models\UserInvitation;
use Modules\Invitation\Transformers\GetInvites;
use Modules\Invitation\Http\Requests\OpenInvite;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Invitation\Http\Requests\SendInvitation;
use Modules\Invitation\Http\Controllers\InvitationController;
use Modules\Invitation\Contracts\InvitedUserControllerInterface;

class WebInvitedUserController extends InvitationController implements InvitedUserControllerInterface
{

    public function __construct(private InvitedUserService $InvitedUserService)
    {

    }
    public function invitationMethodPage()
    {

        return Inertia::render('InvitationMethod/InvitationMethod');

    }

    public function inviteFriendsAndFamilyPage()
    {

        return Inertia::render('InviteFriendsAndFamily/InviteFriendsAndFamily');

    }

    public function sendInviteByEmail(SendInvitation $request)
    {

        try {
            InertiaCognito::addUserNameToSession(Auth::user()->user_name);
            $this->InvitedUserService->processEmailInvitation($request->validated());

            request()->session()->flash('success', 'Invitation sent successfully!');
            return redirect()->back()->with('success', true);
        } catch (\App\Exceptions\ErrorException $e) {

            throw new InertiaException('InviteFriendsAndFamily/InviteFriendsAndFamily', $e);

        }
    }


    // Add your methods here

    public function openInvitation(Request $request)
    {
        try {
            $result = $this->InvitedUserService->invitationOpened($request->all());

            request()->session()->flash('success', 'Invitation sent successfully!');
            return redirect()->back();
        } catch (\App\Exceptions\ErrorException $e) {

            throw new InertiaException('Welcome/welcomeInvite', $e);

        }

    }

    public function getInvitationList(Request $request)
    {
        return GetInvites::collection($this->InvitedUserService->userInvitations($request->all()));
    }

    public function getConnectedUsers(Request $request) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ConnectedUsers::collection($this->InvitedUserService->getUserList());
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
        $this->InvitedUserService->removeUser($request->validated());
        request()->session()->flash('success', 'Invitation sent successfully!');
        return redirect()->back();
    }
    public function welcomeZyanazaPage()
    {
        return Inertia::render('WelcomeZyanaza/WelcomeZyanaza');
    }
    public function InvestmentDashboardPage()
    {
        return Inertia::render('InvestmentDashboard/InvestmentDashboard');
    }
}


