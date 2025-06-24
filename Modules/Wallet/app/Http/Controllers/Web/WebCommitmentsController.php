<?php

namespace Modules\Wallet\Http\Controllers\Web;
use ErrorException;
use Inertia\Inertia;
use Modules\Invitation\Transformers\ConnectedUsers;
use Modules\Wallet\Contracts\CommitmentsControllerInterface;
use Modules\Wallet\Http\Requests\AddDepositRequest;
use Modules\Wallet\Http\Requests\GetCommitmentDetails;
use Modules\Wallet\Http\Requests\GetRecentTransactionRequest;
use Modules\Wallet\Http\Requests\PaySomeoneRequest;
use Modules\Wallet\Services\CommitmentsService;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Transformers\Commitments;
use Modules\Wallet\Transformers\GetCommitment;
use Modules\Wallet\Transformers\GetRecentTransaction;

class WebCommitmentsController extends WalletController implements CommitmentsControllerInterface
{

    public function __construct(private CommitmentsService $CommitmentsService)
    {

    }

    public function paySomeone(PaySomeoneRequest $request)
    {
        try {

            $commitment = $this->CommitmentsService->createCommitment($request->validated());

            return back()->with('success', 'Commitment created successfully.');

        } catch (\App\Exceptions\ErrorException $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
//    public function addDeposit(AddDepositRequest $request)
//    {
//        return new Commitments($this->CommitmentsService->createCommitment($request->validated()));
//    }
    public function addDeposit(AddDepositRequest $request)
    {
        try {

            $commitment = $this->CommitmentsService->createCommitment($request->validated());

            return back()->with('success', 'Add deposit successfully.');

        } catch (\App\Exceptions\ErrorException $e) {

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function allCommitments()
    {
        $commitments = $this->CommitmentsService->userCommitments();
        return Inertia::render('ContributionDetails/ContributionDetails', [
            'commitments' => $commitments,
        ]);
    }
    public function contributionSummary()
    {
        return Inertia::render('SummaryMainCard/summaryMainCard');
    }

    public function commitmentDetails(GetCommitmentDetails $request)
    {
        $data = $request->validated();

        $commitmentDetails = new GetCommitment($this->CommitmentsService->getCommitmentDetails($request->validated()), $data['contributer_type'] );
        return Inertia::render('SummaryMainCard/summaryMainCard', [
            'commitmentDetails' => $commitmentDetails,

        ]);
    }
    public function frinedAndFamilyPage()
    {

        $connectedUser = $this->CommitmentsService->userCommitments();
        return Inertia::render('Friends&Family/Friends&Family' ,[
            'contributorsList'=> $connectedUser,
        ]);
    }

    public function contributionDetailCardPage()
    {
        return Inertia::render('ContributionIndividualDetailCard/ContributionIndividualDetailCard');
    }

    public function getRecentTransaction(GetRecentTransactionRequest $request)
    {

        $data = $request->validated();

        GetRecentTransaction::setContributerType($data['contributer_type']);

        $transactionDetails =  GetRecentTransaction::collection( $this->CommitmentsService->getRecentTransactionDetails($request->validated()));

        return Inertia::render('ContributionIndividualDetailCard/ContributionIndividualDetailCard', [
            'transactionDetails'  => $transactionDetails
        ]);
    }


}