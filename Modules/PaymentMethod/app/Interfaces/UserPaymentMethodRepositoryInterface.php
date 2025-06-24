<?php

namespace Modules\PaymentMethod\Interfaces;

use Modules\PaymentMethod\DTOs\PaymentMethodDTO;
use Modules\PaymentMethod\Models\PaymentMethod;

interface UserPaymentMethodRepositoryInterface
{

    public function save(PaymentMethodDTO $paymentMethodDTO): PaymentMethod;

    public function delete(PaymentMethodDTO $paymentMethodDTO): bool;

}