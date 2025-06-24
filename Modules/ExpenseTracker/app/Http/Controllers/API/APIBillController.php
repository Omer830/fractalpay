<?php

namespace Modules\ExpenseTracker\Http\Controllers\API;

use Modules\ExpenseTracker\Models\Bill;
use Modules\ExpenseTracker\Services\BillService;
use Modules\ExpenseTracker\Transformers\GetBill;
use Modules\ExpenseTracker\Transformers\PendingBills;
use Modules\ExpenseTracker\Http\Resources\ApiResponse;
use Modules\ExpenseTracker\Http\Requests\AddBillRequest;
use Modules\ExpenseTracker\Http\Requests\GetBillRequest;
use Modules\ExpenseTracker\Http\Requests\PayBillRequest;
use Modules\ExpenseTracker\Http\Requests\DeleteBillRequest;
use Modules\ExpenseTracker\Http\Requests\AssignContributors;
use Modules\ExpenseTracker\Contracts\BillControllerInterface;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;

class APIBillController extends ExpenseTrackerController implements BillControllerInterface
{

    public function __construct(
        private BillService $BillService
    )
    {
        
    }

    public function storeBill(AddBillRequest $request)
    {
        return new GetBill($this->BillService->storeBill($request->validated()));
    }

    public function getBills()
    {
        return GetBill::collection($this->BillService->getUserBills());
    }

    public function deleteBill(DeleteBillRequest $request)
    {
       
        return GetBill::collection($this->BillService->deleteBill($request->validated()));
        
    }
    public function getContributorBills()
    {
        return GetBill::collection($this->BillService->getContributorBills());
    }

    public function getBill(GetBillRequest $request)
    {
        return new GetBill($this->BillService->getIndividual($request->validated()['id']));
    }

    public function payBill(PayBillRequest $request)
    {
        return $this->BillService->createCommitment($request->validated());
    }
    
    public function assignContributors(AssignContributors $request)
    {
        return new ApiResponse ( $this->BillService->assingContributors($request->validated()));
    }

    public function pendingBills()
    {
        $pendingBills = $this->BillService->getPendingBills();
        return PendingBills::collection($pendingBills);
    }


}