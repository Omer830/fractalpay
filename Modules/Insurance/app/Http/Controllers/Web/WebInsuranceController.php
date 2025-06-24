<?php


namespace Modules\Insurance\Http\Controllers\Web;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Modules\Options\Transformers\CategoryList;
use Modules\Options\Services\OptionListService;
use Modules\Insurance\Services\InsuranceService;
use Modules\Insurance\Http\Requests\InsuranceRequest;
use Modules\Insurance\Http\Resources\InsuranceResource;
use Modules\Insurance\Http\Controllers\InsuranceController;
use Modules\Insurance\Contracts\InsuranceContractControllerInterface;
use Modules\Insurance\Models\Insurance;
use Modules\Insurance\Models\InsuranceUser;
use Modules\UserKyc\DTO\UserDTO;

class WebInsuranceController extends InsuranceController implements InsuranceContractControllerInterface
{
    private InsuranceService $insuranceService;
    private UserDTO $userDTO;

    public function __construct(InsuranceService $insuranceService , OptionListService $optionListService)
    {
        $this->insuranceService = $insuranceService;
        $this->optionListService = $optionListService;
        $this->userDTO = new UserDTO(Auth::user());
    }

    // Add your methods here
//    public function storeInsurancePremiumDetails(InsuranceRequest $request)
//    {
//        // Assuming createInsurance returns the created insurance model or null if failed
//        $insurance = $this->insuranceService->createInsurance($request->validated());
//
//        if (!$insurance) {
//            // Handle the failure case, maybe redirect back with an error
//            return Inertia::location(route('insurance.form'))->with('error', 'Failed to save insurance details.');
//        }
//
//        // Assuming there is an index or some page to display success
//        return Inertia::location(route('insurance.index'))->with('success', 'Insurance details saved successfully.');
//    }
    public function storeInsurancePremiumDetails(InsuranceRequest $request)
    {
        try {
            $insurance = $this->insuranceService->createInsurance($request->validated());
            request()->session()->flash('success', 'Insurance premium details stored successfully!');
            return Inertia::render('InsuranceQuestion/InsuranceQuestion', [
                'successMessage' => 'Insurance added successfully',
                'insurance' => new InsuranceResource($insurance),
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing insurance premium details: ' . $e->getMessage());

            return Inertia::render('InsuranceQuestion/InsuranceQuestion', [
                'errors' => ['error' => 'An error occurred while processing your request.'],
            ]);
        }
    }


//    public function insuranceQuestionPage()
//    {
//        $documents = $this->optionListService->getOptionsList([
//            'categories' => 'insurance_names',
//            'parent' => Auth::user()->country,
//        ]);
//        $response =  CategoryList::make($documents)->toArray(request());
//        return Inertia::render('InsuranceQuestion/insuranceQuestion', $response);
//
//    }



    public function insuranceQuestionPage()
    {
//        Auth::user()->country
        $insuranceNames = $this->optionListService->getOptionsList([
            'categories' => 'insurance_names',
            'parent' => 'australia',
        ]);

        $termsPeriods = $this->optionListService->getOptionsList([
            'categories' => 'terms_periods',
        ]);

        $response = [
            'insuranceNames' => $insuranceNames,
            'termsPeriods' => $termsPeriods
        ];
        return Inertia::render('InsuranceQuestion/InsuranceQuestion', [
            'insuranceNames' => $response['insuranceNames'],
            'termsPeriods' => $response['termsPeriods'],

        ]);
    }
    public function fetchInsurancePremiumDetails()
    {
        try {
            // Fetch insurance names for 'Australia'
            $insuranceNames = $this->optionListService->getOptionsList([
                'categories' => 'insurance_names',
                'parent' => 'australia',
            ]);
            // Fetch terms periods
            $termsPeriods = $this->optionListService->getOptionsList([
                'categories' => 'terms_periods',
            ]);

            // Fetch insurance data
            $insuranceData    = $this->insuranceService->fetchInsurance();
            
            $insuranceDocument = $this->userDTO->user()->getFirstMediaUrl('insurance-certificate');
            $insuranceDetails = $insuranceData ? new InsuranceResource($insuranceData, $insuranceDocument) : null;
        
            // Combine all fetched data into one array for Inertia
            return Inertia::render('InsuranceQuestion/InsuranceQuestion', [
                'insuranceNames' => $insuranceNames,
                'termsPeriods' => $termsPeriods,
                'insuranceDetails' => $insuranceDetails,
            ]);

        } catch (\Exception $e) {
            // Log the error and return an appropriate error response
            Log::error('Failed to fetch details: ' . $e->getMessage());
            return Inertia::render('Error', ['message' => 'Failed to fetch details.']);
        }
    }


}