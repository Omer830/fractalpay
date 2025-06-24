<?php


namespace Modules\PaymentMethod\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\PaymentMethod\Http\Requests\DeletePaymentMethodRequest;
use Modules\PaymentMethod\Http\Requests\StorePaymentMethod;
use Modules\PaymentMethod\Services\UserPaymentMethodService;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\PaymentMethod\Http\Requests\LinkPaymentMethodRequest;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;
use Modules\PaymentMethod\Contracts\UserPaymentMethodControllerInterface;
use Twilio\TwiML\Voice\Redirect;


class WebUserPaymentMethodController extends PaymentMethodController implements UserPaymentMethodControllerInterface
{

    public function __construct(
        private UserPaymentMethodService $UserPaymentMethodService
    )
    {

    }
    public function index()
    {
        $paymentMethods = $this->UserPaymentMethodService->getPaymentMethods();
        $paymentMethodsResource = GetPaymentMethodResource::collection($paymentMethods);

        return Inertia::render('PaymentMethods/PaymentMethods', [
            'paymentMethods' => $paymentMethodsResource,
            'successMessage' => 'Payment methods retrieved successfully.'
        ]);
    }
    public function getPaymentMethod(Request $request)

    {
        $paymentMethods = $this->UserPaymentMethodService->getPaymentMethods($request->all());
        
        $paymentMethodsResource = GetPaymentMethodResource::collection($paymentMethods);

        return Inertia::render('PaymentMethods/PaymentMethods', [
            'paymentMethods' => $paymentMethodsResource,
            'successMessage' => 'Payment methods retrieved successfully.'
        ]);
    }
    public function storePaymentMethod(StorePaymentMethod $request)
    {

        $paymentMethod = $this->UserPaymentMethodService->newPaymentMethod($request->validated());

        $data = [];
        
        $this->UserPaymentMethodService->setPaymentMethodStatus($data);

        $paymentMethodResource = new GetPaymentMethodResource($paymentMethod);
        
        return Inertia::render('PaymentMethods/PaymentMethods', [
            'paymentMethod' => $paymentMethodResource,
            'successMessage' => 'Payment method stored successfully.'
        ]);
    }

    public function deletePaymentMethod(DeletePaymentMethodRequest $request): RedirectResponse
    {
        $this->UserPaymentMethodService->deletePaymentMethod($request->validated());

        return redirect()->back()->with('success', 'Payment method deleted successfully');
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





