<?php

namespace Modules\Insurance\Http\Controllers\API;

use Modules\Insurance\Contracts\InsuranceContractControllerInterface;
use Modules\Insurance\Services\InsuranceService;
use Modules\Insurance\Http\Controllers\InsuranceController;
use Modules\Insurance\Http\Requests\InsuranceRequest;
use Modules\Insurance\Http\Resources\InsuranceResource;

class APIInsuranceController extends InsuranceController implements InsuranceContractControllerInterface
{
    private InsuranceService $insuranceService;
    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    // Add your methods here
    public function storeInsurancePremiumDetails(InsuranceRequest $request)
    {
        return new InsuranceResource($this->insuranceService->createInsurance($request->validated()));
    }
    public function fetchInsurancePremiumDetails()
    {
        
        return new InsuranceResource($this->insuranceService->fetchInsurance());
        
    }
}