<?php

namespace Modules\ExpenseTracker\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\WalletUser;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\Repositories\UserRepository;
use Modules\ExpenseTracker\DTO\CommitmentDTO;
use Modules\UserKyc\Services\UserProfileService;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Traits\NotifiableTrait;
use Modules\Auth\Interfaces\UserRepositoryInterface;
use Modules\ExpenseTracker\Notifications\PayUserBill;
use Modules\ExpenseTracker\Notifications\AssignBillToUser;
use Modules\Wallet\Interfaces\CommitmentsRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\BillRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\VisitRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\ExpensesRepositoryInterface;

class BillService
{
    use NotifiableTrait;

    private UserDTO $userDTO;

    public function __construct(
        private BillRepositoryInterface $BillRepository,
        private CommitmentsRepositoryInterface $CommitmentsRepository,
        private ExpensesService $ExpensesService,
        private UserRepositoryInterface $UserRepository
        
    )
    {
        $this->userDTO = new UserDTO(Auth::user());
    }

    public function storeBill(array $data)
    {
    
        

            $bill = $this->BillRepository->create($this->userDTO, $data);

            $contributorsIds = $data['contributorsIds'] ?? [];

            if (!empty($contributorsIds)) {
                $this->ExpensesService->sendBillAssignNotification($this->userDTO,$contributorsIds);
                $bill->contributors()->syncWithoutDetaching($contributorsIds);
                $contributors = $this->UserRepository->get($contributorsIds);
                $message = "A new bill '{$bill->category}' from visit '{$bill->visit->name}' has been assigned to you by'{$this->userDTO->getName()}'";
                $this->sendNotification($message, $bill->user->id);
                Notification::send($contributors, new AssignBillToUser($bill));
            }


            return $bill;


    }

    public function getUserBills()
    {
        return $this->BillRepository->get($this->userDTO);
    }

    public function deleteBill($data)
    {
       $this->BillRepository->delete($data['bill_id']);

       return $this->getUserBills();
    }
    public function getContributorBills()
    {
        return $this->BillRepository->getContributorBill($this->userDTO);
    }

    public function getIndividual(int $id)
    {
        return $this->BillRepository->find($id);
    }

    public function createCommitment($data)
    {
       
        $bills        =  $this->BillRepository->getBills($data);

        $commitments  =  [];
       
        $multipleBill = count($bills) > 1 ? true : false;
        
        foreach ($bills as $bill) {

            $dataNew             =      $data;

            $dataNew['bill_id']  =      $bill->id;

            $commitmentDTO       =      new CommitmentDTO($dataNew, multipleBill: $multipleBill);
            if ($this->userDTO->id !== $bill->user_id) {
                
                $message = "Your bill of '{$bill->amount}'  has Been Paid By '{$this->userDTO->getName()}' .";
                $this->sendNotification($message, $bill->user->id);
                Notification::send($this->userDTO, new PayUserBill($bill));
            }

            $commitments[]       =      $this->CommitmentsRepository->create($commitmentDTO);
        }
    
        return $commitments;
    }
    
    public function assingContributors(array $data)
    {

       $bill             =   $this->BillRepository->getById($data['bill_id']);

       $contributorsIds  =   $data['contributorsIds'] ?? [];

        if (!empty($contributorsIds)) {

            $bill->contributors()->syncWithoutDetaching($contributorsIds);
            $contributors = $this->UserRepository->get($contributorsIds);
            Notification::send($contributors, new AssignBillToUser($bill));


        }

        return [

            'message' => 'Contributors Assigned Successfully',

            'data'    => [
                'bill_id'     =>     $bill->id,
               'contributors' => $bill->contributors()->select('users.id')->pluck('id'),
            ],
            
        ];

    }

    public function getPendingBills(): Collection
    {
        return $this->BillRepository->getPendingBills($this->userDTO);
    }
}