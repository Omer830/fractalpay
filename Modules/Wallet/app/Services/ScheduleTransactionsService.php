<?php

namespace Modules\Wallet\Services;

use Modules\Wallet\Interfaces\ScheduleTransactionsRepositoryInterface;

class ScheduleTransactionsService
{

    public function __construct(private ScheduleTransactionsRepositoryInterface $ScheduleTransactionsRepository)
    {

    }

    public function create(array $data)
    {

    }
}