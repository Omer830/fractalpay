<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Modules\Wallet\Contracts\ScheduleTransactionsControllerInterface;
use Modules\Wallet\Services\ScheduleTransactionsService;
use Modules\Wallet\Http\Controllers\WalletController;

class WebScheduleTransactionsController extends WalletController implements ScheduleTransactionsControllerInterface
{

    public function __construct(private ScheduleTransactionsService $ScheduleTransactionsService)
    {
        
    }

    // Add your methods here
}