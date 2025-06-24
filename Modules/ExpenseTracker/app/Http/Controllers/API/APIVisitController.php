<?php

namespace Modules\ExpenseTracker\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\ExpenseTracker\Services\VisitService;
use Modules\ExpenseTracker\Transformers\GetVisit;
use Modules\ExpenseTracker\Transformers\GetSingleVisit;
use Modules\ExpenseTracker\Http\Requests\AddVisitRequest;
use Modules\ExpenseTracker\Transformers\VisitContributor;
use Modules\ExpenseTracker\Http\Requests\DeleteBillRequest;
use Modules\ExpenseTracker\Http\Requests\DeleteVisitRequest;
use Modules\ExpenseTracker\Http\Requests\UpdateVisitRequest;
use Modules\ExpenseTracker\Contracts\VisitControllerInterface;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;

class APIVisitController extends ExpenseTrackerController implements VisitControllerInterface
{

    public function __construct(private VisitService $VisitService)
    {
        
    }

    public function storeVisit(AddVisitRequest $request)
    {
        return new GetVisit($this->VisitService->addVisit($request->validated()));
    }

    public function UpdateVisit(UpdateVisitRequest $request, Visit $visit)
    {
        return new GetVisit($this->VisitService->updateVisit($request->validated(), $visit));
    }

    public function addVisitPage()
    {
        $data['visitTypes']= $this->ExpensesService->fetchAllVisitType();
        $data['contributors'] = '';
        return $data;
    }

    public function allVisits()
    {
        return GetVisit::collection($this->VisitService->getAllVisits());
    }
    public function getContributorVisit()
    {

        return VisitContributor::collection($this->VisitService->getContributorVisits());

    }

    public function getVisit(Visit $visit)
    {
        return new GetSingleVisit($visit);
    }
    public function deleteVisit( DeleteVisitRequest $request)
    {
        $deleted = $this->VisitService->deleteVisit($request->validated());
        if ($deleted) {
            
            return response()->json(['message' => 'Visit deleted successfully.'], 200);
        }
        
        return response()->json(['message' => 'Visit not found or could not be deleted.'], 404);

    }

    public function getVisitByOrganization(Request $request)
    {
        return $this->VisitService->getVisitByOrganization($request->all());

    }
    public function assignContributors(Request $request)
    {
        $request->validate([
            'visit_id' => ['required', 'integer', 'exists:visits,id'],
            'contributorsIds' => ['required', 'array'],
            'contributorsIds.*' => ['required', 'integer', 'exists:users,id'],
        ]);
    
        $visit = Visit::findOrFail($request->visit_id);
        $updatedVisit = $this->VisitService->assignContributors($visit, $request->contributorsIds);
    
        return response()->json([
            'message' => 'Contributors assigned successfully.',
            'data' => new GetSingleVisit($updatedVisit),
        ], 200);
    }

    
}