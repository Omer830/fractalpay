<?php

namespace Modules\PaymentMethod\Services;

use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\PaymentMethod\DTOs\UserDTO;
use Modules\PaymentMethod\Models\PaymentUser;
use Modules\PaymentMethod\DTOs\PaymentMethodDTO;
use Modules\PaymentMethod\Enums\PaymentMethodTypes;
use Modules\PaymentMethod\Services\StripeAccountService;
use Modules\PaymentMethod\Factories\PaymentRepositoryFactory;
use Modules\PaymentMethod\Interfaces\UserPaymentMethodRepositoryInterface;

class UserPaymentMethodService
{

    public function __construct(
        private StripeAccountService $StripeAccountService,
        private UserPaymentMethodRepositoryInterface $userPaymentMethodRepository,
    )
    {
        $this->userDTO = new UserDTO();
    }

    public function newPaymentMethod($data)
    {

        try {
            $paymentMethodDTO = new PaymentMethodDTO($data);
            $paymentRepository = $paymentMethodDTO->repository();
            return $paymentRepository->save($paymentMethodDTO);
        }
        catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', previous: $e);

        }


    }

    public function deletePaymentMethod($data)
    {
        try {
            $paymentMethodDTO = new PaymentMethodDTO($data);
            $paymentRepository = $paymentMethodDTO->repository();
            return $paymentRepository->delete($paymentMethodDTO);
        }
        catch (\Exception $e) {
            throw new ErrorException('DELETE_UNSUCCESSFUL', previous: $e);
        }
    }

    public function getPaymentMethods($data)
    {
        
        $paymentMethodDTO = new PaymentMethodDTO($data);
        return $this->userPaymentMethodRepository->get($paymentMethodDTO);
    }

    public function setPaymentMethodStatus($data)
    {
        $isPayment = count($this->getPaymentMethods($data)) > 0 ? true : false;

        Session::put('PaymentStatus', [
            'isPayment' => $isPayment,
        ]);
    }

    public function newUserIntent()
    {
        return $this->userDTO->user()?->createSetupIntent();
    }

    public function assignPaymentMethod($data)
    {

        //Todo: $externalAccount = $this->userDto->primaryAccount; use primary method
        $externalAccount = new PaymentMethodDTO([
            'account_type' => 'credit_card',
            'payment_method_id' => $data['payment_method_id']
        ]);

        return $this->StripeAccountService->createExternalAccount($externalAccount, $this->userDTO->user()->asStripeAccount());

    }

}