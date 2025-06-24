<?php

namespace Modules\ExpenseTracker\Services;

use Modules\Auth\Models\User;
use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Support\Facades\Notification;
use Modules\ExpenseTracker\Transformers\GetBill;
use Modules\ExpenseTracker\Traits\NotifiableTrait;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Modules\ExpenseTracker\Notifications\AssignVisitToUser;
use Modules\ExpenseTracker\Interfaces\VisitRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\ExpensesRepositoryInterface;

class VisitService
{
    use NotifiableTrait;
    
    private UserDTO $userDTO;

    public function __construct(private ExpensesRepositoryInterface $ExpensesRepository, private VisitRepositoryInterface $VisitRepository,  private BillService $BillService,private UserRepositoryInterface $UserRepository)
    {
        $this->userDTO = new UserDTO(Auth::user());
    }

    public function getAllVisits()
    {
        try {
            return $this->VisitRepository->getAllVisits($this->userDTO);
        } catch (\Exception $e) {
            throw new \ErrorException('Error getting  visits: ' . $e->getMessage(), 500, $e);

        }
    }

    public function addVisit(array $data)
    {
        try {

            $contributorsIds = $data['contributorsIds'] ?? [];

            unset($data['contributorsIds']);

            $visit = $this->VisitRepository->create($data);

            if (!empty($contributorsIds)) {

                $visit->contributors()->attach($contributorsIds);
                $contributors = $this->UserRepository->get($contributorsIds);
                $message = "A new Visit '{$visit->name}' has been assigned to you by '{$visit->owner->first_name}'.";
                foreach ($contributors as  $contributor) {
                    $this->sendNotification($message, $contributor->id);
                }
                
                Notification::send($contributors, new AssignVisitToUser($visit));
            }
           
           
           $this->addSessioin();


            return $visit;

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, previous: $e);

        }
    }
    
    public function deleteVisit($data)
    {
           
        $visit = $this->VisitRepository->find($data['visit_id']);

        if ($visit) {
            $visit->delete();
            return true;
        }
        
        return false;
    }

    public function addSessioin()
    {
        $contributorAttribute   = [
            'key'   => 'isContributorVisit',
            'value' => count($this->getContributorVisits()) > 0 ? true : false,
        ];
        
        $ownerBillAttribute     = [
            'key'   => 'isOwnerVisit',
            'value' => count($this->getUserVisits()) > 0 ? true : false,
        ];

        $attributes             = [$contributorAttribute, $ownerBillAttribute];

        $this->userDTO->user()->createOrUpdateAttributes($attributes);

        $contributorAttribute = $this->userDTO->user()->attributes()->where('key', 'isContributorVisit')->first();
        $ownerAttribute = $this->userDTO->user()->attributes()->where('key', 'isOwnerVisit')->first();
        
        
        $isContributorVisit = $contributorAttribute ? $contributorAttribute->value == 1 : false;
        $isOwnerVisit = $ownerAttribute ? $ownerAttribute->value == 1 : false;
    
        // Putting into the Session
        session(['isContributorVisit' => $isContributorVisit]);
        session(['isOwnerVisit' => $isOwnerVisit]);

      
    }

    public function updateVisit(array $data, Visit $visit)
    {

        try {

            $contributorsIds = $data['contributorsIds']?? [];
            unset($data['contributorsIds']);

            if($visit->user_id && $visit->user_id !== $this->userDTO->id) {
                throw new ErrorException('NOT_ALLOWED');
            }

            $visit = $this->VisitRepository->update($visit, $data);

            if (!empty($contributorsIds)) {
                $visit->contributors()->sync($contributorsIds);
                $contributors = $this->UserRepository->get($contributorsIds);
                Notification::send($contributors, new AssignVisitToUser($visit));
                
            }

            return $visit;

        } catch (\Exception $e) {

            throw new \ErrorException('Something went wrong during the update', 500, 1, __FILE__, __LINE__, $e);

        }

    }

    public function getVisitByOrganization(array $data)
    {
        try {

            return $this->VisitRepository->findByOrganization($data['id']);
        } catch (\Exception $e) {
            throw new \ErrorException('Error adding visit: ' . $e->getMessage(), 500, $e);
        }
    }

    public function getContributorVisits()
    {
        return $this->VisitRepository->getContributorVisit($this->userDTO);
    }

    public function getUserVisits()
    {
        return $this->VisitRepository->getAllVisits($this->userDTO);
    }

    public function assignContributors(Visit $visit, array $contributorsIds)
    {
        try {
            // Get currently attached contributor IDs
            $existingContributors = $visit->contributors()->pluck('users.id')->toArray();

            // Find new contributors that are not already attached
            $newContributors = array_diff($contributorsIds, $existingContributors);

            // Attach only new contributors
            if (!empty($newContributors)) {
                $visit->contributors()->attach($newContributors);
            }

            // Reload visit with updated contributors
            return $visit->load('contributors');
        } catch (\Exception $e) {
            throw new \Exception('Failed to assign contributors: ' . $e->getMessage(), 500);
        }
    }
    
}