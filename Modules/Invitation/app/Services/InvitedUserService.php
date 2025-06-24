<?php

namespace Modules\Invitation\Services;

use Mockery\Exception;
use App\Exceptions\ErrorException;
use Illuminate\Support\Collection;
use Modules\Auth\Enums\CommonKeys;
use Modules\Invitation\DTO\UserDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Middleware\InertiaCognito;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Support\Facades\Response;
use Modules\ExpenseTracker\Models\Visit;
use Modules\Auth\Services\CognitoService;
use Illuminate\Support\Facades\Notification;
use Modules\Invitation\Models\UserInvitation;

use Modules\Invitation\Enums\InvitationStatus;
use Modules\Auth\Repositories\HelperRepository;
use Modules\Auth\Services\APICognitoCacheService;
use Modules\ExpenseTracker\Traits\NotifiableTrait;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Modules\Invitation\Notifications\SendUserInvitation;
use Modules\ExpenseTracker\Notifications\AssignBillToUser;
use Modules\Invitation\Notifications\AcceptUserInvitation;
use Modules\ExpenseTracker\Notifications\AssignVisitToUser;
use Modules\Invitation\Interfaces\InvitedUserRepositoryInterface;

class InvitedUserService
{
    use NotifiableTrait;
    private $userDTO;
    public function __construct(
        private InvitedUserRepositoryInterface $InvitedUserRepository,
        
        private CognitoService $cognitoService,
        private UserRepositoryInterface $userRepository,
    )
    {
        $this->userDTO = new UserDTO(Auth::user());
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function processEmailInvitation(array $data)
    {

        $users = $this->InvitedUserRepository->getUsers($data['email']);

         $this->sendInviteExistingUsers($users,$data['bill_id'] ?? '',$data['visit_id'] ?? '');

        $existingEmails = $users->pluck('email')->toArray();

        // Find emails that are not in the users table
        $nonExistentEmails = collect(array_diff($data['email'], $existingEmails))->map(function ($email) {
            return ['email' => $email];
        });

        $this->sendInviteToNoneUsers($nonExistentEmails);

        return Config::get('flashMessages.INVITATION_SENT');

    }

    /**
     * @param Collection $users
     * @return void
     */
    private function sendInviteExistingUsers(Collection $users,  $billId = null,$visitId = null)
{

    $invitedUsers = [];

    foreach ($users as $user) {
        $userDTO = new UserDTO($user);
        $invitation = $this->InvitedUserRepository->createInvitationForExistingUser($this->userDTO, $userDTO, $billId);

          if(isset($billId) && $billId != ''){
       
        $bill = Bill::findOrFail($billId);
        $bill->contributors()->syncWithoutDetaching($user);
        $message = "A new bill '{$bill->category}' from visit '{$bill->visit->name}' has been assigned to you by'{$this->userDTO->name()}'";
        
        $this->sendNotification($message, $bill->user->id);
        Notification::send($user, new AssignBillToUser($bill));
    }
     if(isset($visitId) && $visitId != ''){
       
        $visit = Visit::findOrFail($visitId);
        $visit->contributors()->syncWithoutDetaching($user);
        $message = "A new Visit '{$visit->name}' has been assigned to you by '{$this->userDTO->name()}'.";
        
        $this->sendNotification($message, $user->id);
        Notification::send($user, new AssignVisitToUser($visit));
    }
    
        $invitedUsers[] = $invitation;
    }

    if ($users->isNotEmpty()) {
        $inviterName = $this->userDTO->name();
        $message = "You have been invited and assigned to a bill by {$inviterName}";

        $this->sendNotification($message, $invitedUsers[0]['id']);

        Notification::send($users, new SendUserInvitation($this->userDTO, $billId));
    }
}
    

    /**
     * @param Collection $users
     * @return void
     */
    private function sendInviteToNoneUsers(Collection $users)
    {

        $invitedUser = [];

        foreach($users as $user)
        {
            $invitedUser[] = $this->InvitedUserRepository->createInvitationForNewUser($this->userDTO, $user);
        }

        Notification::send($invitedUser, new SendUserInvitation($this->userDTO));

    }

    /**
     * @param array $data
     * @return Collection
     */
    public function userInvitations(array $data = [])
    {
        $filters = $data['filters'] ?? [];
        return $this->InvitedUserRepository->getInvitations($this->userDTO, $filters);
    }

    /**
     * @param $data
     * @return false|null
     * @throws ErrorException
     */
    public function invitationOpened($data)
    {
        try {
            if(!ISSET($data[CommonKeys::REFEREE_CODE->value])) {
                return false;
            }

            $code = $data[CommonKeys::REFEREE_CODE->value];

            if(request()->wantsJson() && ISSET($data[CommonKeys::UNIQUE_CODE->value])) {
                APICognitoCacheService::storeCodeToCache($data[CommonKeys::UNIQUE_CODE->value], $code);
            }
            else {
                request()->session()->regenerate();
                InertiaCognito::addCodeToSession($code);
            }

            $this->InvitedUserRepository->updateInvitationStatusByCode($code, InvitationStatus::OPENED);

            return config('flashMessages.INVITATION_OPENED');

        }
        catch (Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }

    }

    /**
     * @param $data
     * @return bool|void
     * @throws ErrorException
     */
    public function invitationAccepted($data, ?UserInvitation $invitation = null)
    {


        try {

            if($invitation) {
                return $this->InvitedUserRepository->updateInvitation($invitation, $data);
            }

            $code = $data[CommonKeys::REFEREE_CODE->value] ?? null;

            if(!$code && !$invitation) {
                return false;
            }

            $data = [
                'invitation_status' => InvitationStatus::ACCEPTED,
                'invited_user'  =>  $this->userDTO->id(),
                'name'  =>  $this->userDTO->name(),
                'email' =>  $this->userDTO->email(),
                'phone_number'    =>  $this->userDTO->phone()
            ];

            $response = false;

            if($code) {
                $response =  $this->InvitedUserRepository->updateInvitationByCode($code, $data);
            }



            if($response) {
                return config('flashMessages.INVITATION_ACCEPTED');
            }

            throw new ErrorException('INVITATION_NOT_FOUND');

        }
        catch (Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }
    }

    /**
     * @param $data
     * @return bool|void
     * @throws ErrorException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function invitationRejected($data, ?UserInvitation $invitation = null) {

        try {

            if($invitation) {
                return $this->InvitedUserRepository->updateInvitation($invitation, [
                    'invitation_status' => InvitationStatus::REJECTED,
                ]);
            }

            $code = $data[CommonKeys::REFEREE_CODE->value] ?? null;

            if(request()->wantsJson() && ISSET($data[CommonKeys::UNIQUE_CODE->value])) {
                $code = $code ?? APICognitoCacheService::getCodeFromCache($data['uniqueCode']);
                APICognitoCacheService::deleteCodeFromCache($data['uniqueCode']);
            }
            else if(!request()->wantsJson()) {
                $code = session()->get(CommonKeys::REFEREE_CODE->value);
                InertiaCognito::removeCodeFromSession();
            }

            if($code) {
                return $this->InvitedUserRepository->updateInvitationStatusByCode($code, InvitationStatus::REJECTED);
            }

            throw new ErrorException('INVITATION_NOT_FOUND');


        }
        catch (Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }

    }


    /**
     * @return Collection
     */
    public function getUserList()
    {
        return $this->InvitedUserRepository->getAcceptedUsers($this->userDTO);
    }

    
    public function getPendingUserList()
    {
        return $this->InvitedUserRepository->getPendingUsers($this->userDTO);
    }

    /**
     * @param $user
     * @return bool|null
     * @throws ErrorException
     */
    public function removeUser($data)
    {
        $user       = $this->userRepository->find($data['user_id']);
        
        $invitation = $this->InvitedUserRepository->findByUserId($this->userDTO, $user->id);
 
        if(!$invitation) {
            throw new ErrorException('INVITATION_NOT_FOUND');
        }

        return $this->InvitedUserRepository->deleteInvitation($invitation);

    }
    /**
     * @param $refreeCode
     * @param $user
     * 
     */
    public function acceptNotificationToInviter($invitedUser,$refreeCode)
    {
        $invitation=$this->InvitedUserRepository->findByCode($refreeCode);
        $inviter=$this->userRepository->find($invitation->inviter->id);
        if($invitation)
        {
            Notification::send($invitedUser, new AcceptUserInvitation($inviter,$refreeCode));
        }
        
    }
}