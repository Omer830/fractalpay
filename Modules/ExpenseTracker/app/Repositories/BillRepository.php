<?php

namespace Modules\ExpenseTracker\Repositories;

use Modules\Auth\Models\User;
use Modules\Options\Models\Options;
use Modules\Auth\Services\UserService;
use Modules\Wallet\Models\Transaction;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Models\Bill;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Modules\Options\Services\OptionListService;

class BillRepository implements \Modules\ExpenseTracker\Interfaces\BillRepositoryInterface
{
    public $optionListService;

    public function __construct(private UserService $userService,OptionListService $optionListService)
    {
        $this->optionListService = $optionListService;

    }

    public function create(UserDTO $userDTO, array $data)
    {
        return $userDTO->user()->bills()->create($data);
    }

    public function get(UserDTO $userDTO)
    {
        return $userDTO->user()->bills()->get();
    }

    public function getContributorBill(UserDTO $userDTO)
    {
        $user = $userDTO->user();
        
        $contributorBills = Bill::whereHas('contributors', function ($query) use ($user) {
            $query->where('users.id', $user->id); 
        })->get(); 

        return  $contributorBills->unique('id');
    }

    public function find($id)
    {
        return Bill::findOrFail($id);
    }

    public function update($id,array $data)
    {
        return tap(Bill::find($id))->update($data);
    }
    public function getBills( $data)
    {
        return Bill::whereIn('id', $data['bill_id'])
                ->orderBy('created_at', 'desc') 
                ->orderBy('updated_at', 'desc') 
                ->get();
    }

    public function getById($id)
    {
        return Bill::find($id);
    }

    public function delete($id)
    {
        $bill = $this->getById($id);
        return $bill->delete();
    }

    public function getPendingBills(UserDTO $userDTO): Collection
    {
        $paidBillIds = Transaction::where('receivable_type', 'Modules\ExpenseTracker\Models\Bill')
            ->pluck('receivable_id');

        return Bill::where('user_id', $userDTO->id)
            ->whereNotIn('id', $paidBillIds)
            ->get();
    }
}