<?php

namespace Modules\Wallet\Services;

use Stripe\File;
use Carbon\Carbon;
use Stripe\Stripe;
use Modules\Wallet\DTO\UserDTO;
use App\Exceptions\ErrorException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\WalletUser;
use Modules\Wallet\Models\Transaction;
use Modules\Wallet\DTO\PaymentMethodDTO;
use Modules\Wallet\Interfaces\WalletRepositoryInterface;

class WalletService
{

    public function __construct(
        private WalletRepositoryInterface $WalletRepository,
        private StripeAccountService $StripeAccountService,
        private TransactionService   $TransactionService,
    )
    {
        $this->userDto = new UserDTO(Auth::user());
        Stripe::setApiKey(env("STRIPE_SECRET"));
    }

    public function getUserWalletAmount()
    {
        return $this->userDto->wallet();
    }

    public static function depositFunds(WalletUser $user, int|float $amount)
    {
        return $user->wallet->deposit($amount);
    }

    public static function withdrawFunds(WalletUser $user, int|float $amount)
    {
        return $user->wallet->withdraw($amount);
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

    public function linkExternalAccount()
    {
        //Todo: $externalAccount = $this->userDto->primaryAccount; use primary method
        $externalAccount = new PaymentMethodDTO([
            'account_type' => 'credit_card',
            'country' => 'AU',
            'currency' => 'AUD',
            'account_holder_name' => 'Zeeshan Masood Sabri',
            'account_holder_type' => 'individual',
            'swift_code' => '013943',
            'account_number' => '612966710',
        ]);

        return $this->StripeAccountService->createExpernalAccount($externalAccount, $this->userDto->user()->asStripeAccount());
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

    public function getLatestBillPaid(): array
    {
        return $this->TransactionService->getBillPaymentAnalytics($this->userDto);
    }

    public function getWalletHistory(): array
    {
        $now = now();
        
        return [
            'monthly' => [
                'receive' => $this->calculateReceiveAmount(
                    $this->TransactionService->getMonthlyReceiveTransactions($this->userDto, $now)
                ),
                'send' => $this->calculateSendAmount(
                    $this->TransactionService->getMonthlySendTransactions($this->userDto, $now)
                )
            ],
            'weekly' => [
                'receive' => $this->calculateReceiveAmount(
                    $this->TransactionService->getWeeklyReceiveTransactions($this->userDto, $now)
                ),
                'send' => $this->calculateSendAmount(
                    $this->TransactionService->getWeeklySendTransactions($this->userDto, $now)
                )
            ],
            'daily' => [
                'receive' => $this->calculateReceiveAmount(
                    $this->TransactionService->getDailyReceiveTransactions($this->userDto, 6)
                ),
                'send' => $this->calculateSendAmount(
                    $this->TransactionService->getDailySendTransactions($this->userDto, 6)
                )
            ]
        ];
    }
    
    protected function calculateReceiveAmount(Collection $transactions): float
    {
        return $transactions->sum('amount');
    }
    
    protected function calculateSendAmount(Collection $transactions): float
    {
        return $transactions->sum('amount');
    }

}