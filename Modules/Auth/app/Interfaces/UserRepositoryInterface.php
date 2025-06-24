<?php

namespace Modules\Auth\Interfaces;

use Modules\Auth\Models\User;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(array $data);

    public function update($id, array $data);

    public function verifyUser(User $user, array $data);

    public function delete($id);

    public function find($id) : User|Null;

    public function findByEmail($email) : User | Null | Collection;
    
    public function findByEmailLogin($email) : User | Null | Collection;

    public function findByPhone($phone) : User | Null;

    public function findByEmailOrPhone(array | string $emailOrPhone) : User | Null;

    public function findByEmailAndPhone($email, $phone) : User | Null;

    public function findByEmailOrPhoneNumber($emailOrPhone): User|null;

    public function findByUsername($username);

    public function findByPasswordResetToken($token);

    public function get(array $ids): \Illuminate\Support\Collection;

    public function getAllVisits($id) : User | Visit | Collection | Null;

}
