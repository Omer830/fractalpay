<?php

namespace Modules\ExpenseTracker\Services;

use Modules\Auth\Models\User;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseTracker\DTO\UserDTO;
use Illuminate\Validation\ValidationException;
use Modules\ExpenseTracker\Traits\NotifiableTrait;
use Modules\ExpenseTracker\Interfaces\VisitRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\ExpensesRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\OrganisationRepositoryInterface;

class ExpensesService
{
    use NotifiableTrait;

    private UserDTO $userDTO;

    public function __construct(private ExpensesRepositoryInterface $ExpensesRepository,private VisitRepositoryInterface $VisitRepositorty)
    {
        $this->userDTO = new UserDTO(Auth::user());
    }

    public function getUserExpenseStats()
    {
        return $this->ExpensesRepository->fetchStats($this->userDTO);
    }

    public function getVisitBillsDetails($data)
    {
        return $this->ExpensesRepository->fetchVisitBills($this->userDTO, $data);
    }

    public function getProviderDetails(array $data)
    {
        try {
          
            $providerNumber = $data['provider_number'];

            $doctor = $this->ExpensesRepository->findDocByProvider($providerNumber);
            
            if ($doctor) {
                $data['doctor'] = $doctor;
                $data['organization'] = $doctor->parent ? $doctor->parent->parent : null;
            } else {
                $data['doctor'] = null;
                $data['organization'] = null;
            }
            
            return $data;
    
        } catch (ValidationException $e) {
            
            throw new ErrorException('INVALID_PROVIDER_DATA', 500, $e);
    
        } catch (\Exception $e) {
            throw new ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }

    public function fetchAllVisitType()
    {
        try {
            return $this->ExpensesRepository->getAllVisitType();
        } catch (\Exception $e) {
            throw new ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }

    public function fetchAllOrganizatioin()
    {
        try {
            return $this->ExpensesRepository->getAllOrganization();
        } catch (\Exception $e) {
            throw new ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }

    public function sendInvitationNotification($userId, $inviter)
    {
        $message = "You have received an invitation from {$inviter}.";
        $this->sendNotification($message, $userId);
    }

    public function sendBillAssignNotification(UserDTO $userDTO, array $contributorsIds)
    {
        
        $contributors = User::whereIn('id', $contributorsIds)->get();  

        
        foreach ($contributors as $contributor) {
        $message = "{$userDTO->getName()}  have assigned you Bill.";
            $this->sendNotification($message, $contributor->id);  
        
        }

    }

    public function getUserNotifications()
    {
        return $this->VisitRepositorty->getUserNotifications($this->userDTO->id);
    }
    
   
}