<?php

namespace Modules\Wallet\Services;

use Modules\Wallet\Interfaces\InsuranceRepositoryInterface;

class InsuranceService
{
    protected $InsuranceRepository;
    public function __construct(InsuranceRepositoryInterface $InsuranceRepository)
    {
        $this->InsuranceRepository = $InsuranceRepository;
    }

    // Add your methods here
    public function insurancePremiumDetail(array $data)
    {
        try {
            dd($data);
            $response = $this->InsuranceRepository->createInsurancePremiumDetail($data);
            
            return $response;

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, $e);

        }

    }
}