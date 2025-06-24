<?php

namespace Modules\ExpenseTracker\Http\Controllers\API;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Modules\ExpenseTracker\Services\BillService;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Transformers\GetVisit;
use Modules\ExpenseTracker\Services\ExpensesService;
use Modules\ExpenseTracker\Http\Requests\AddBillRequest;
use Modules\ExpenseTracker\Http\Requests\AddVisitRequest;
use Modules\ExpenseTracker\Http\Requests\PayingVisitBills;
use Modules\ExpenseTracker\Transformers\VisitBillsCategory;
use Modules\ExpenseTracker\Http\Requests\UpdateVisitRequest;
use Modules\ExpenseTracker\Http\Requests\ProviderDetailsRequest;
use Modules\ExpenseTracker\Contracts\ExpensesControllerInterface;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;
use Modules\ExpenseTracker\Http\Requests\PayingVisitsBillsContributor;

class APIExpensesController extends ExpenseTrackerController implements ExpensesControllerInterface
{

    public function __construct(private ExpensesService $ExpensesService,private VisitService $VisitService, private BillService $BillService)
    {
        
    }

    public function getProviderDetails(ProviderDetailsRequest $request)
    {
        return ['data' => $this->ExpensesService->getProviderDetails($request->validated())];
    }

    public function stats()
    {
        return ['data' => $this->ExpensesService->getUserExpenseStats()];
    }

    public function getVisitAndBills(PayingVisitBills $request)
    { 
        
        return new VisitBillsCategory($this->ExpensesService->getVisitBillsDetails($request->validated()));
    }

    public function getAllOrganizations()
    {
        return $this->ExpensesService->fetchAllOrganizatioin();

    }

    public function billCategory()
    {
        return $this->BillService->fetchAllBillCategory();
    }

    public function getMyNotifications()
    {
        $notifications = $this->ExpensesService->getUserNotifications();

        return response()->json([
            'status' => true,
            'data' => $notifications,
        ]);
    }
    

    
    // Add your methods here
}