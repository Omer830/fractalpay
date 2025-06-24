<?php

namespace Modules\Wallet\Contracts;
use Modules\Auth\Http\Requests\InsuranceRequest;

interface InsuranceContractControllerInterface
{
    // Add your methods here
    public function registerInsurancePremiumDetails(InsuranceRequest $request);
}