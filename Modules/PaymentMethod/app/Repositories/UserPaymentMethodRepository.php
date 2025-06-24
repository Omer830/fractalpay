<?php

namespace Modules\PaymentMethod\Repositories;

use App\Exceptions\ErrorException;
use Illuminate\Support\Collection;
use Modules\PaymentMethod\DTOs\PaymentMethodDTO;
use Modules\PaymentMethod\Enums\PaymentMethodTypes;
use Modules\PaymentMethod\Models\PaymentMethod;

class UserPaymentMethodRepository implements \Modules\PaymentMethod\Interfaces\UserPaymentMethodRepositoryInterface
{

    public function save(PaymentMethodDTO $paymentMethodDTO): PaymentMethod
    {
        throw new ErrorException('NOT_FOUND');
    }

    public function get(PaymentMethodDTO $paymentMethodDTO) : Collection
    {
        $query = $paymentMethodDTO->user()->paymentMethod();

        if($type = $paymentMethodDTO->accountType()) {
            $query->where('type', $type);
        }

        return $query->get();
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