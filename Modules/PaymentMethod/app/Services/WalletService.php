<?php

namespace Modules\PaymentMethod\Services;

use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\DTO\PaymentMethodDTO;
use Modules\Wallet\DTO\UserDTO;
use Modules\Wallet\Interfaces\WalletRepositoryInterface;
use Modules\Wallet\Models\WalletUser;
use Stripe\Stripe;
use Stripe\File;
class WalletService
{

    public function __construct(
        private WalletRepositoryInterface $WalletRepository,
        private StripeAccountService $StripeAccountService
    )
    {
        $this->userDto = new UserDTO(Auth::user());
        Stripe::setApiKey(env("STRIPE_SECRET"));
    }

    public function createUserStripeAccount()
    {


        try {

            $user = $this->userDto->user();

            if (env('APP_ENV') !== 'production') {
                if ($user->hasStripeAccount()) {
                    $user->deleteStripeAccount();
                }
            }

            return $this->StripeAccountService->create($user);

        }
        catch (\Exception $e) {

            throw new ErrorException('UN_SUCCESSFUL', previous: $e);

        }
//        $stripeAccount = $user->asStripeAccount();
//
//        $externalAccounts = $stripeAccount->external_accounts;
//
//        if(ISSET($externalAccounts['data'])) {
//            $accounts = collect($externalAccounts['data']);
//        }
//        $stripeAccount->updateExternalAccount();





        //$stripe->files->create([
        //  'purpose' => 'dispute_evidence',
        //  'file' => $fp
        //]);
//
//        $validated = [
//          'returnURL'   =>  'https://ezysoft.solutions',
//          'refreshURL'  =>  'https://ezysoft.solutions'
//        ];

//        $user->refresh();
//
//        return $user->asStripeAccount();

//        return $user->accountOnboardingUrl($validated['returnURL'], $validated['refreshURL']);

    }


    public function getUserStripeAccount()
    {
        return $this->StripeAccountService->getAccount($this->userDto->user());
    }

    public function updateUserStripeAccount()
    {

        set_time_limit(300);


        $user = $this->userDto->user();

        $front =  File::create([
            'purpose'   =>  'identity_document',
            'file'  =>  fopen('testing/front.jpeg', 'r')
        ]
        );

        $back = File::create([
            'purpose'   =>  'identity_document',
            'file'  =>  fopen('testing/back.jpeg', 'r')
        ]
        );

//        $user->updateStripeAccount([
//            'individual'    => [
//                'verification'  =>  [
//                    'document'  =>  [
//                        'front' =>  $front['id'],
//                        'back'  =>  $back['id']
//                    ]
//                ]
//            ]
//
//        ]);


        return $user->asStripeAccount();

    }

}