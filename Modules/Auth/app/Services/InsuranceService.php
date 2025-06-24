<?php

namespace Modules\Auth\Services;

use Modules\Auth\Interfaces\InsuranceRepositoryInterface;

class InsuranceService
{
    protected $insuranceRepository;
    public function __construct(InsuranceRepositoryInterface $insuranceRepository)
    {
        $this->insuranceRepository = $insuranceRepository;
    }

    // Add your methods here
    public function insurancePremiumDetail(array $data)
    {
        try {
            dd($data);
            $response = $this->insuranceRepository->createInsurancePremiumDetail($data);
            
            return $response;

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, $e);

        }

    }
}