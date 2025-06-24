<?php

namespace Modules\Insurance\Contracts;
use Modules\Insurance\Http\Requests\InsuranceRequest;

interface InsuranceContractControllerInterface
{
    // Add your methods here
    public function storeInsurancePremiumDetails(InsuranceRequest $request);
}