<?php

namespace Modules\ExpenseTracker\Http\Controllers\Web;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Wallet\Services\WalletService;
use Modules\Options\Services\OptionListService;
use Modules\Wallet\Transformers\WalletResource;
use Modules\ExpenseTracker\Services\BillService;
use Modules\ExpenseTracker\Transformers\GetBill;
use Modules\UserKyc\Services\UserProfileService;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Transformers\GetVisit;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\ExpenseTracker\Services\ExpensesService;
use Modules\ExpenseTracker\Http\Requests\AddVisitRequest;
use Modules\ExpenseTracker\Http\Requests\PayingVisitBills;
use Modules\ExpenseTracker\Transformers\VisitBillsCategory;
use Modules\PaymentMethod\Services\UserPaymentMethodService;
use Modules\ExpenseTracker\Http\Requests\ProviderDetailsRequest;
use Modules\PaymentMethod\Transformers\GetPaymentMethodResource;
use Modules\ExpenseTracker\Contracts\ExpensesControllerInterface;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;
use Modules\ExpenseTracker\Http\Requests\PayingVisitsBillsContributor;

class WebExpensesController extends ExpenseTrackerController implements ExpensesControllerInterface
{

    public function __construct(private ExpensesService $ExpensesService,
                                private VisitService $VisitService ,
                                OptionListService $optionListService,
                                private InvitedUserService $InvitedUserService,
                                private BillService $BillService,
                                private WalletService $WalletService,
                                private UserPaymentMethodService $UserPaymentMethodService,
                                private UserProfileService $UserProfileService
    )
    {
        $this->optionListService = $optionListService;
    }

    public function showExpenseTrackerPage()
    {
        
        $this->VisitService->addSessioin();

        $visitType = $this->optionListService->getOptionsList([
            'categories' => 'visit_type',
        ]);

         $accepted = $this->InvitedUserService->getUserList();
         $pending = $this->InvitedUserService->getPendingUserList();
         $contributors = $accepted->merge($pending);

        $visitsList = GetVisit::collection($this->VisitService->getAllVisits());

        $contributorVisitList = GetVisit::collection($this->VisitService->getContributorVisits());

        $billList = GetBill::collection($this->BillService->getUserBills());

        $contributorBillList = GetBill::collection($this->BillService->getContributorBills());

        // $connectedUser = ConnectedUsers::collection($this->InvitedUserService->getUserList());

        $expenseStats = $this->ExpensesService->getUserExpenseStats();

        $connectedUser = $accepted->merge($pending);
        
        return Inertia::render('ExpenseTracker/ExpenseTracker', [
            'visitType' => $visitType,
            'billList' => $billList,
            'contributorBillList'=>$contributorBillList,
            'contributorsList' => $connectedUser,
            'expenseStats' => $expenseStats,
            'visitsList' => $visitsList,
            'contributorVisitList' => $contributorVisitList,
        ]);
    }



    public function showAddContributorsPage()
    {
        return Inertia::render('AddContributors/AddContributors');
    }
    public function showTotalPayablePage()
    {
        return Inertia::render('PaymentBillSummary/TotalPayable');
    }
    public function getVisitAndBills(PayingVisitBills $request)
{
    // Retrieve the validated data
    $billsDetails = new VisitBillsCategory($this->ExpensesService->getVisitBillsDetails($request->validated()));

    return Inertia::render('PaymentBillSummary', [
        'billsDetails' => $billsDetails
    ]);
}

    public function showPaymentSummaryPage()
    {
        return Inertia::render('PaymentBillSummary/PaymentBillSummary');
    }

    public function showAddBillPage(Request $request)
    {

        $paymentMethods = $this->UserPaymentMethodService->getPaymentMethods($request->all());

        $paymentMethodsResource = GetPaymentMethodResource::collection($paymentMethods);
        $walletData = new WalletResource($this->WalletService->getUserWalletAmount());
        return Inertia::render('AddBills/AddBills' ,[
            'wallet' => $walletData,
            'paymentMethods' => $paymentMethodsResource,
        ]);
    }



    // Todo: Implement
    public function allVisits()
    {
        $visits=$this->VisitService->getAllVisits();

    }
    public function addVisitPage()
    {
        return $this->ExpensesService->fetchAllVisitType();
    }
    public function storeVisit(AddVisitRequest $request)
    {
        try {

            $this->VisitService->addVisit($request->validated());

            return redirect()->back()->with([
                'success' => 'Visit added successfully!',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to add the visit. Please try again.'
            ]);
        }
    }
    public function getProviderDetails(ProviderDetailsRequest $request)
    {
        $providerDetails = $this->ExpensesService->getProviderDetails($request->validated());

        return Inertia::render('ExpenseTracker/ExpenseTracker', [
            'providerDetails' => $providerDetails,
        ]);
    }

    public function getMyNotifications()
    {
        // Fetch user notifications
        $notifications = $this->ExpensesService->getUserNotifications();

        // Return plain JSON response
        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }


    // Todo: Implement Bill Functionality
}