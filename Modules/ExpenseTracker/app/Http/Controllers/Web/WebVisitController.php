<?php

namespace Modules\ExpenseTracker\Http\Controllers\Web;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\ExpenseTracker\Contracts\VisitControllerInterface;
use Modules\ExpenseTracker\Http\Requests\AddVisitRequest;
use Modules\ExpenseTracker\Http\Requests\UpdateVisitRequest;
use Modules\ExpenseTracker\Models\Visit;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;
use Modules\ExpenseTracker\Transformers\GetSingleVisit;
use Modules\ExpenseTracker\Transformers\GetVisit;
use Modules\Invitation\Services\InvitedUserService;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Options\Services\OptionListService;

class WebVisitController extends ExpenseTrackerController implements VisitControllerInterface
{

    public function __construct(private VisitService $VisitService,
                                OptionListService $optionListService,
                                private InvitedUserService $InvitedUserService,
    )
    {
        $this->optionListService = $optionListService;
    }

//    public function storeVisit(AddVisitRequest $request)
//    {
//        return new GetVisit($this->VisitService->addVisit($request->validated()));
//    }

    public function storeVisit(AddVisitRequest $request)
    {
        try {
            $visit = $this->VisitService->addVisit($request->validated());

            return redirect()->back()->with([
                'success' => 'Visit added successfully!',
                'visit' => new GetVisit($visit),
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Failed to add the visit. Please try again.',
                'exception_message' => $e->getMessage(), // Optionally, for debugging
            ]);
        }
    }


    public function UpdateVisit(UpdateVisitRequest $request, Visit $visit)
    {
        return new GetVisit($this->VisitService->updateVisit($request->validated(), $visit));
    }

    public function addVisitPage()
    {
        $data['visitTypes'] = $this->ExpensesService->fetchAllVisitType();
        $data['contributors'] = '';
        return $data;
    }
    public function showAddManualBillPage()
    {
        $visitType = $this->optionListService->getOptionsList([
            'categories' => 'visit_type',
        ]);
        $billCategory = $this->optionListService->getOptionsList([
            'categories' => 'bills',
        ]);
        $allVisits = GetVisit::collection($this->VisitService->getAllVisits());
         $accepted = $this->InvitedUserService->getUserList();
         $pending = $this->InvitedUserService->getPendingUserList();
         $contributors = $accepted->merge($pending);
         $connectedUser = $accepted->merge($pending);

        // $connectedUser = ConnectedUsers::collection($this->InvitedUserService->getUserList());
        return Inertia::render('AddBillsManually/AddBillsManually', [
            'allVisits' => $allVisits,
            'contributorsList' => $connectedUser,
            'visitType' => $visitType,
            'billCategory' => $billCategory,
        ]);
    }

    public function allVisits()
    {
        return GetVisit::collection($this->VisitService->getAllVisits());
    }


    public function getVisitByOrganization(Request $request)
    {
        return $this->VisitService->getVisitByOrganization($request->all());

    }
    public function getBillsAgainstVisit()
    {
        return Inertia::render('PaymentBillSummary/PaymentBillSummary', [

        ]);
    }
    public function getBillsAgainstVisitList(Request $request)
    {
        $visitIds = $request->input('visits');

        if (!$visitIds || !is_array($visitIds)) {
            return response()->json(['error' => 'Invalid visit IDs'], 400);
        }

        // Fetch visits based on IDs
        $visits = Visit::whereIn('id', $visitIds)->get();

        // Transform visit data
        $formattedVisits = GetSingleVisit::collection($visits);
        $connectedUser = ConnectedUsers::collection($this->InvitedUserService->getUserList());
        return Inertia::render('PaymentBillSummary/PaymentBillSummary', [
            'billsDetails' => $formattedVisits,
            'contributorsList' => $connectedUser,
        ]);
    }


}