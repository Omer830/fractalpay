<?php

namespace Modules\PaymentMethod\DTOs;

use Illuminate\Support\Facades\Auth;
use Modules\PaymentMethod\Models\PaymentUser;
use Modules\Wallet\Models\WalletUser;

class UserDTO {

    private $user;

    public function __construct()
    {
        $this->user = WalletUser::find(Auth::id());
    }

    public function user()
    {
        return $this->user;
    }

}