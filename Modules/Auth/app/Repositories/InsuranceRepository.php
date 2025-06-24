<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Interfaces\InsuranceRepositoryInterface;
use Modules\Auth\Models\User;

class InsuranceRepository implements InsuranceRepositoryInterface {

    public function create(array $data)
    {
        return User::create($data);
    }
}
