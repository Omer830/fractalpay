<?php

namespace Modules\Auth\Http\Controllers\API;


use Modules\Insurance\Transformers\InsuranceResource;
use Modules\Wallet\Contracts\InsuranceContractControllerInterface;
use Modules\Auth\Services\InsuranceService;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Auth\Http\Requests\InsuranceRequest;

class APIInsuranceController extends WalletController implements InsuranceContractControllerInterface
{
    private InsuranceService $insuranceService;
    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    // Add your methods here
    public function registerInsurancePremiumDetails(InsuranceRequest $request)
    {
        return new InsuranceResource($this->insuranceService->insurancePremiumDetail($request->validated()));
    }
}