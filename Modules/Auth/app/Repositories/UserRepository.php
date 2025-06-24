<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\User;
use Modules\ExpenseTracker\Models\Visit;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Collection;
use Modules\Auth\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        return User::find($id)->update($data);
    }

    public function verifyUser($user, $data)
    {
        if(ISSET($data['email'])) {
            $user->update(['email_verified_at' => now(), 'otp' => null]);
            return $user;
        }
        if(ISSET($data['secondary_email'])) {
            $user->update(['secondary_email_verified_at' => now(), 'secondary_email_otp' => null]);
            return $user;
        }
        if(ISSET($data['alternative_email'])) {
            $user->update(['alternative_email_verified_at' => now(), 'alternative_email_otp' => null]);
            return $user;
        }
        if(ISSET($data['phone_number'])) {
            $user->update(['phone_verified_at' => now(), 'otp' => null]);
            return $user;
        }
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }

    public static function findBySub($sub)
    {
        return User::where('sub', $sub)->first();
    }

    public function find($id) : User|Null
    {
        return User::find($id);
    }

    public function findByEmail($email) : User|Null|Collection
    {
        $query = User::query();
    
        if (is_array($email)) {
                return $query->where(function($q) use ($email) {
                    $q->whereIn('email', $email)
                    ->orWhereIn('secondary_email', $email)
                    ->orWhereIn('alternative_email', $email);
                })->get();
        }
        
        return $query->where(function($q) use ($email) {
                $q->where('email', $email)
                ->orWhere('secondary_email', $email)
                ->orWhere('alternative_email', $email);
            })->first();
    }

    public function findByEmailLogin($email): User|Null|Collection
    {
        $email = strtolower($email);
 
        $query = User::query();

         if (is_array($email)) {
        return $query->where(function($q) use ($email) {
            $q->whereIn('email', $email)
                ->whereNotNull('email_verified_at')
            ->orWhere(function($q) use ($email) {
                $q->whereIn('secondary_email', $email)
                    ->whereNotNull('secondary_email_verified_at');
            })
            ->orWhere(function($q) use ($email) {
                $q->whereIn('alternative_email', $email)
                    ->whereNotNull('alternative_email_verified_at');
            });
        })->get();
    }

    return $query->where(function($q) use ($email) {
            $q->where('email', $email)
                ->whereNotNull('email_verified_at')
            ->orWhere(function($q) use ($email) {
                $q->where('secondary_email', $email)
                    ->whereNotNull('secondary_email_verified_at');
            })
            ->orWhere(function($q) use ($email) {
                $q->where('alternative_email', $email)
                    ->whereNotNull('alternative_email_verified_at');
            });
        })->first();
    }

    public function findByPhone($phone) : User|Null
    {
        return User::where('phone_number', $phone)->first();
    }

    public function findByEmailOrPhone($emailOrPhone) : User|Null
    {
        if(is_array($emailOrPhone)) {
            if(isset($emailOrPhone['email']) && isset($emailOrPhone['phone_number'])){
                return $this->findByEmailAndPhone($emailOrPhone['email'], $emailOrPhone['phone_number']);
            }
            if(isset($emailOrPhone['email'])) {
                return $this->findByEmail($emailOrPhone['email']);
            }
            if(isset($emailOrPhone['secondary_email'])) {
                return $this->findByEmail($emailOrPhone['secondary_email']);
            }
            if(isset($emailOrPhone['alternative_email'])) {
                return $this->findByEmail($emailOrPhone['alternative_email']);
            }
            if(isset($emailOrPhone['phone_number'])) {
                return $this->findByPhone($emailOrPhone['phone_number']);
            }

        } else {
            return $this->findByEmailOrPhoneNumber($emailOrPhone);
        }

        return null;
    }

    public function findByEmailAndPhone($email, $phone) : User|Null
    {
        $email = strtolower($email);
        return User::where('email', $email)
        ->where('phone_number', $phone)
        ->first();
    }

    public function findByEmailOrPhoneNumber($emailOrPhone): User|null
    {
        $emailOrPhone = strtolower($emailOrPhone);

        return User::where('email', $emailOrPhone)
            ->orWhere('phone_number', $emailOrPhone)
            ->first();
    }

    public function findByUsername($username) : User|Null
    {
        return User::where('username', $username)->first();
    }

    public function findByPasswordResetToken($token)
    {
        return PasswordReset::where('token', $token)->first();
    }
    public function get(array $ids): \Illuminate\Support\Collection
    {
        return User::whereIn('id', $ids)->get();
    }
    public function getAllVisits($id) : User | Visit | Collection | Null
    {
        $user=$this->find($id);
        return $user->visits()->get();
    }

}
