<?php

namespace Modules\Wallet\Http\Controllers\API;

use Modules\Wallet\Services\WalletService;
use Modules\Wallet\Transformers\WalletResource;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Transformers\WalletHistoryResource;
use Modules\Wallet\Contracts\WalletControllerInterface;
use Modules\Wallet\Transformers\LatestBillPaidResource;

class APIWalletController extends WalletController implements WalletControllerInterface
{

    public function __construct(private WalletService $WalletService)
    {
        
    }

    public function userWallet()
    {
        return new WalletResource($this->WalletService->getUserWalletAmount());
    }

    public function createAccount()
    {
        return $this->WalletService->createUserStripeAccount();
    }

    public function getAccount(){
        return $this->WalletService->getUserStripeAccount();
    }

    public function linkAccount()
    {
        return $this->WalletService->linkExternalAccount();
    }

    public function updateAccount()
    {
        return $this->WalletService->updateUserStripeAccount();
    }
    public function walletHistory()
    {
        $walletHistory = $this->WalletService->getWalletHistory();
        
        return new WalletHistoryResource($walletHistory);
    }
    public function latestBillPaid()
    {
        $latestBills = $this->WalletService->getLatestBillPaid();
        
        return new LatestBillPaidResource($latestBills);
    }
    
}