<?php

namespace Modules\ExpenseTracker\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Http\Requests\DeleteVisitRequest;
use Modules\ExpenseTracker\Models\Visit;
use Modules\Documents\Services\MediaService;
use Modules\ExpenseTracker\Services\BillService;
use Modules\ExpenseTracker\Transformers\GetBill;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Http\Resources\ApiResponse;
use Modules\ExpenseTracker\Transformers\GetSingleVisit;
use Modules\ExpenseTracker\Http\Requests\AddBillRequest;
use Modules\ExpenseTracker\Http\Requests\PayBillRequest;
use Modules\ExpenseTracker\Http\Requests\DeleteBillRequest;
use Modules\ExpenseTracker\Http\Requests\AssignContributors;
use Modules\ExpenseTracker\Contracts\BillControllerInterface;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;

class WebBillController extends ExpenseTrackerController implements BillControllerInterface
{
    private UserDTO $userDTO;

    public function __construct(
        private BillService $BillService,
        private VisitService $VisitService,
        private MediaService $MediaService,
    )
    {
            $this->userDTO = new UserDTO(Auth::user());

    }

    public function storeBill(AddBillRequest $request)
    {
        try {

            $bill = $this->BillService->storeBill($request->validated());
        
            if ($request->hasFile('billFile')) {
                $this->MediaService->uploadBill(
                    $bill,
                    'billFile',
                    ['uploaded_by' => $this->userDTO->user()->id]
                );
            }
            
            return redirect()->back()->with([
                'success' => 'Bill added successfully!',
                'bill' => new GetBill($bill)
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors([
                'error' => 'Failed to add the visit. Please try again.',
                'exception_message' => $e->getMessage(), // Optionally, for debugging
            ]);
        }
    }
    public function payBill(PayBillRequest $request)
    {
        try {

            $commitment = $this->BillService->createCommitment($request->validated());

            return redirect()->back()->with([
                'success' => 'Bill payment successful!',
                'commitment' => $commitment
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors([
                'error' => 'Failed to process the bill payment. Please try again.',
                'exception_message' => $e->getMessage(),
            ]);
        }
    }



    public function deleteBill(DeleteBillRequest $request)
    {

        try {
            $bill = GetBill::collection($this->BillService->deleteBill($request->validated()));

            return redirect()->back()->with([
                'success' => 'Bill deleted  successfully!',
                'bill' => $bill
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors([
                'error' => 'Failed to delete bill',
                'exception_message' => $e->getMessage(), // Optionally, for debugging
            ]);
        }
    }
    public function deleteVisit(DeleteVisitRequest $request): RedirectResponse
    {
        try {
            $deleted = $this->VisitService->deleteVisit($request->validated());

            return redirect()->back()->with(
                $deleted
                    ? ['success' => 'Visit deleted successfully.']
                    : ['error' => 'Visit not found or could not be deleted.']
            );
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'An error occurred while deleting the visit.',
                // 'exception_message' => $e->getMessage(), // Optional during dev
            ]);
        }
    }




    public function getBills()
    {
        return GetBill::collection($this->BillService->getUserBills());

    }
//    public function assignContributors(AssignContributors $request)
//    {
//        $contributors = $this->BillService->assingContributors($request->validated());
//
//        return inertia('ExpenseTracker/ExpenseTracker', [
//            'contributors' => $contributors
//        ]);
//    }

    public function assignContributors(Request $request)
    {
        $request->validate([
            'visit_id' => ['required', 'integer', 'exists:visits,id'],
            'contributorsIds' => ['required', 'array'],
            'contributorsIds.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $visit = Visit::findOrFail($request->visit_id);
        $updatedVisit = $this->VisitService->assignContributors($visit, $request->contributorsIds);

        // Fetch updated contributors
        $contributors = $updatedVisit->contributors; // Assuming there's a relation

        return inertia('ExpenseTracker/ExpenseTracker', [
            'message' => 'Contributors assigned successfully.',
            'visit' => new GetSingleVisit($updatedVisit),
            'contributors' => $contributors,
        ]);
    }
    public function assignContributorsToBill(AssignContributors $request)
    {
        $result = $this->BillService->assingContributors($request->validated());

        return Inertia::render('payment-bill-summary', [
            'message' => 'Contributors assigned successfully.',
            'data' => $result,
        ]);
    }

}