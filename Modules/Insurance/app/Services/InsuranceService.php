<?php

namespace Modules\Insurance\Services;

use Modules\Insurance\DTO\UserDTO;
use Modules\Insurance\Interfaces\InsuranceRepositoryInterface;
use App\Exceptions\ErrorException;
use Illuminate\Validation\ValidationException;
class InsuranceService
{
    protected $insuranceRepository;
    private UserDTO $userDTO;

    public function __construct(InsuranceRepositoryInterface $insuranceRepository)
    {
        $this->insuranceRepository = $insuranceRepository;
        $this->userDTO = new UserDTO();
    }

    // Add your methods here
    public function createInsurance(array $data)
    {
        try {

            if($this->userDTO->has_insurance()) {
                $this->insuranceRepository->delete($this->userDTO);
            }

            return $this->insuranceRepository->create($this->userDTO, $data);

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, $e);

        }

    }
    public function fetchInsurance()
    {
        try {
            return $this->insuranceRepository->get($this->userDTO);

        } catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', 500, $e);

        }

    }
}