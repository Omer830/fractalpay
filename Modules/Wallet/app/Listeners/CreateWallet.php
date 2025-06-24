<?php

namespace Modules\Wallet\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Wallet\Models\WalletUser;

class CreateWallet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $walletUser = WalletUser::find($event->user?->id);

        if($walletUser){
            $walletUser->wallet()->updateOrCreate([]);
        }

    }
}
