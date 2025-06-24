<?php

namespace Modules\Wallet\Http\Controllers\Web;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseTracker\Transformers\PendingBills;
use Modules\Wallet\Services\CommitmentsService;
use Modules\Wallet\Services\WalletService;
use Modules\Wallet\Transformers\GetCommitment;
use Modules\Wallet\Services\TransactionService;
use Modules\Wallet\Transformers\GetLatestCommitments;
use Modules\Wallet\Transformers\LatestBillPaidResource;
use Modules\Wallet\Transformers\WalletHistoryResource;
use Modules\Wallet\Transformers\WalletResource;
use Modules\ExpenseTracker\Services\BillService;
use Modules\Insurance\Services\InsuranceService;
use Modules\UserKyc\Services\UserProfileService;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Http\Requests\GetCommitmentDetails;
use Modules\Wallet\Contracts\WalletControllerInterface;


class WebWalletController extends WalletController implements WalletControllerInterface
{
    private InsuranceService $insuranceService;
    public function __construct(private WalletService $WalletService,InsuranceService $insuranceService,private InvitedUserService $InvitedUserService ,private TransactionService $TransactionService, private BillService $BillService,private VisitService $VisitService, private UserProfileService $UserProfileService , private CommitmentsService $CommitmentsService)
    {
        
        $this->insuranceService = $insuranceService;
        
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
    public function dashboardPage()
    {

        $walletData = new WalletResource($this->WalletService->getUserWalletAmount());

        $insuranceData = $this->insuranceService->fetchInsurance();

        // $connectedUser= ConnectedUsers::collection($this->InvitedUserService->getUserList());

        $accepted = $this->InvitedUserService->getUserList();
        $pending = $this->InvitedUserService->getPendingUserList();
        $contributors = $accepted->merge($pending);
        $data = [];
        $transactions = $this->TransactionService->getAllTransactions($data);
        $walletHistory = new WalletHistoryResource($this->WalletService->getWalletHistory());

        $latestBills = new LatestBillPaidResource($this->WalletService->getLatestBillPaid());
        $pendingBills = PendingBills::collection($this->BillService->getPendingBills());
        $lastCommitment = $this->CommitmentsService->getLastCommitment();
        $latestCommitment = $lastCommitment
            ? new GetLatestCommitments($lastCommitment)
            : null;

            $contributors = $accepted->merge($pending);

        $this->VisitService->addSessioin();
        session(['profilePic' => $this->UserProfileService->get()->profile_image]);

        return Inertia::render('MainDashboard/MainDashboard', [
            'wallet' => $walletData,
            'insurance'=> $insuranceData,
            'contributorsList'=> ConnectedUsers::collection($contributors),
            'transactionsList'=> $transactions,
            'walletHistory' => $walletHistory,
            'latestBills' => $latestBills,
            'pendingBills' => $pendingBills,
            'latestCommitment' => $latestCommitment,
        ]);
    }

    public function userWallet()
    {
        $walletData = new WalletResource($this->WalletService->getUserWalletAmount());

        return Inertia::render('MainDashboard/MainDashboard', [
            'wallet' => $walletData,
        ]);
    }
    public function transactionHistoryPage()
    {
        return Inertia::render('TransactionHistory/TransactionHistory');
    }
    public function contributionTreePage()
    {
        return Inertia::render('ContributionTree/ContributionTree');
    }
    public function contributionDetailPage()
    {
        return Inertia::render('ContributionDetails/ContributionDetails');
    }
    public function contributionInvitationPage()
    {
        return Inertia::render('Contribution/Contribution');
    }

    public function DepositFundPage()
    {
        return Inertia::render('DepositeFunds/DepositeFunds');
    }
    public function ShareFundsPage()
    {
        return Inertia::render('ShareFunds/ShareFunds');
    }
    public function bpaySummaryPage()
    {
        return Inertia::render('BpaySummary/BpaySummary');
    }
    public function FundTransferSummaryPage()
    {
        return Inertia::render('FundTransferSummary/FundTransferSummary');
    }



    // Add your methods here
}