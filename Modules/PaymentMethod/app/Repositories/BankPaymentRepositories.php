<?php

namespace Modules\PaymentMethod\Repositories;

use Illuminate\Support\Str;
use Modules\PaymentMethod\DTOs\PaymentMethodDTO;
use Modules\PaymentMethod\Interfaces\UserPaymentMethodRepositoryInterface;
use Modules\PaymentMethod\Models\PaymentMethod;
use Modules\Wallet\Models\WalletUser;

class BankPaymentRepositories implements UserPaymentMethodRepositoryInterface
{

    public function save(PaymentMethodDTO $paymentMethodDTO): PaymentMethod
    {
        $attributeData = $paymentMethodDTO->attribute();
        $paymentMethod = $paymentMethodDTO->user()->paymentMethod()->create($paymentMethodDTO->paymentMethodData());
        $paymentMethod->createAttribute($attributeData);
        return $paymentMethod;
    }

    public function delete(PaymentMethodDTO $paymentMethodDTO): bool
    {
       
        $paymentMethod = $paymentMethodDTO->user()
            ->paymentMethod()
            ->where('uuid', $paymentMethodDTO->getMethodId())
            ->firstOrFail();
            
        $paymentMethod->attribute()->delete();
        
        return $paymentMethod->delete();
    }

}