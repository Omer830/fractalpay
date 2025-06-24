<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Modules\Wallet\Contracts\InsuranceContractControllerInterface;
use Modules\Wallet\Services\InsuranceService;
use Modules\Wallet\Http\Controllers\WalletController;

class WebInsuranceController extends WalletController implements InsuranceContractControllerInterface
{

    public function __construct(private InsuranceService $InsuranceService)
    {
        
    }

    // Add your methods here
}