<?php

namespace Modules\Wallet\Contracts;

use Modules\Wallet\Http\Requests\PaySomeoneRequest;

interface CommitmentsControllerInterface
{

    public function paySomeone(PaySomeoneRequest $request);
}