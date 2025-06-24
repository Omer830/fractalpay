<?php

namespace Modules\ExpenseTracker\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\Options\Models\Options;

interface ExpensesRepositoryInterface
{

    public function fetchStats(UserDTO $userDTO): \Illuminate\Support\Collection;

    public function fetchVisitBills(UserDTO $userDTO, array $data): \Illuminate\Support\Collection;
    // Add your methods here
//    public function findDocByProvider($providerNumber) : Options | Null | Collection;
//
//    public function getAllVisitType() : Options | Null | Collection;
//
//    public function getAllOrganization() : Options | Null | Collection;

}