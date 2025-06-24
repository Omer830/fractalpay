<?php

namespace Modules\Wallet\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Wallet\Contracts\ScheduleTransactionsControllerInterface;
use Modules\Wallet\Services\ScheduleTransactionsService;
use Modules\Wallet\Http\Controllers\WalletController;

class APIScheduleTransactionsController extends WalletController implements ScheduleTransactionsControllerInterface
{

    public function __construct(private ScheduleTransactionsService $ScheduleTransactionsService)
    {

    }

    public function createTransaction(Request $request)
    {
        $this->ScheduleTransactionsService->create($request->all());
    }
}