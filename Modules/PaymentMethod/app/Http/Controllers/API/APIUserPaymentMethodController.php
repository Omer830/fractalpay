<?php

namespace Modules\PaymentMethod\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\PaymentMethod\Http\Requests\StorePaymentMethod;
use Modules\PaymentMethod\Services\UserPaymentMethodService;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Http\Requests\LinkPaymentMethodRequest;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;
use Modules\PaymentMethod\Http\Requests\DeletePaymentMethodRequest;
use Modules\PaymentMethod\Contracts\UserPaymentMethodControllerInterface;


class APIUserPaymentMethodController extends PaymentMethodController implements UserPaymentMethodControllerInterface
{

    public function __construct(
        private UserPaymentMethodService $UserPaymentMethodService
    )
    {
        
    }

    public function getPaymentMethod(Request $request)
    {
        return GetPaymentMethodResource::collection($this->UserPaymentMethodService->getPaymentMethods($request->all()));
    }

    public function storePaymentMethod(StorePaymentMethod $request)
    {
        return new GetPaymentMethodResource($this->UserPaymentMethodService->newPaymentMethod($request->validated()));
    }

    public function deletePaymentMethod(DeletePaymentMethodRequest $request)
    {
        $this->UserPaymentMethodService->deletePaymentMethod($request->validated());
        
        return response()->json([
            'message' => 'Payment method deleted successfully'
        ]);
    }


    public function createIntent()
    {
        return $this->UserPaymentMethodService->newUserIntent();
    }

    public function linkPaymentMethod(LinkPaymentMethodRequest $request)
    {
        return $this->UserPaymentMethodService->assignPaymentMethod($request->validated());
    }

}