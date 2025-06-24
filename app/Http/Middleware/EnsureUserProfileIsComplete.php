<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Modules\UserKyc\Services\UserProfileService;
use Modules\PaymentMethod\Services\UserPaymentMethodService;


class EnsureUserProfileIsComplete
{
    public function __construct(private UserProfileService $UserProfileService, private UserPaymentMethodService $UserPaymentMethodService){

    }
    

        /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

   public function handle(Request $request, Closure $next): Response
    {
         $this->UserProfileService->setUserStatus();
         $this->UserProfileService->setUserDocumentsStatus();
         $data = [];
         $this->UserPaymentMethodService->setPaymentMethodStatus($data);
    
        $profileStatus = session()->get('profile_status');
        $proofOfIdentityStatus = session()->get('proofOfIdentityStatus');
        $paymentStatus = session()->get('PaymentStatus');
        $statuses = [
            $profileStatus['completion_percentage'] ?? 0,
            $proofOfIdentityStatus['completion_percentage'] ?? 0,
        ];

        if (collect($statuses)->contains(fn($percent) => $percent < 100)) {
            $inertiaResponse = Inertia::render('Steps/steps', [
                'profile_status' => $profileStatus,
                'proofOfIdentityStatus' => $proofOfIdentityStatus,
                'paymentStatus' => $paymentStatus,
            ]);

            return new Response(
                $inertiaResponse->toResponse($request)->getContent(),
                $inertiaResponse->toResponse($request)->getStatusCode(),
                $inertiaResponse->toResponse($request)->headers->all()
            );
        }
        if($paymentStatus['isPayment'] == false){

            $inertiaResponse = Inertia::render('Steps/steps', [
                'profile_status' => $profileStatus,
                'proofOfIdentityStatus' => $proofOfIdentityStatus,
                'paymentStatus' => $paymentStatus,
            ]);

            return new Response(
                $inertiaResponse->toResponse($request)->getContent(),
                $inertiaResponse->toResponse($request)->getStatusCode(),
                $inertiaResponse->toResponse($request)->headers->all()
            );
        }

        return $next($request);
    }

}