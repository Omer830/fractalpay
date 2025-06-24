<?php

namespace Modules\ExpenseTracker\Interfaces;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\Options\Models\Options;
use Illuminate\Database\Eloquent\Collection;

interface BillRepositoryInterface
{

    public function create(UserDTO $userDTO, array $data);

    public function get(UserDTO $userDTO);

    public function getById(int $id);

    public function update($id, array $data);

    public function delete($id);

    public function getBills( $data);
    
    public function find($id);
    
    public function getContributorBill(UserDTO $userDTO);

    public function getPendingBills(UserDTO $userDTO): Collection;

}