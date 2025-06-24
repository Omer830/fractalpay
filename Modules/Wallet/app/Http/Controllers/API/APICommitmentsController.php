<?php

namespace Modules\Wallet\Http\Controllers\API;

use Modules\Wallet\Transformers\Commitments;
use Modules\Wallet\Transformers\GetCommitment;
use Modules\Wallet\Services\CommitmentsService;
use Modules\Wallet\Http\Requests\AddDepositRequest;
use Modules\Wallet\Http\Requests\PaySomeoneRequest;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Transformers\GetRecentTransaction;
use Modules\Wallet\Http\Requests\GetCommitmentDetails;
use Modules\Wallet\Contracts\CommitmentsControllerInterface;
use Modules\Wallet\Http\Requests\GetRecentTransactionRequest;
use Modules\Wallet\Transformers\GetLatestCommitments;

class APICommitmentsController extends WalletController implements CommitmentsControllerInterface
{

    public function __construct(private CommitmentsService $CommitmentsService)
    {
        
    }

    public function paySomeone(PaySomeoneRequest $request)
    {
        return new Commitments($this->CommitmentsService->createCommitment($request->validated()));
    }

    public function addDeposit(AddDepositRequest $request)
    {
        return new Commitments($this->CommitmentsService->createCommitment($request->validated()));
    }

    public function allCommitments()
    {
        return $this->CommitmentsService->userCommitments();
        
    }

    public function commitmentDetails(GetCommitmentDetails $request)
    {
        $data = $request->validated();
        
        return new GetCommitment($this->CommitmentsService->getCommitmentDetails($request->validated()), $data['contributer_type'] );
    }

    public function getRecentTransaction(GetRecentTransactionRequest $request)
    {
        $data = $request->validated();

        GetRecentTransaction::setContributerType($data['contributer_type']);
        
        return  GetRecentTransaction::collection( $this->CommitmentsService->getRecentTransactionDetails($request->validated()));
    }

    public function lastCommitment()
    {
        $lastCommitment = $this->CommitmentsService->getLastCommitment();
    
        return $lastCommitment 
            ? new GetLatestCommitments($lastCommitment) 
            : response()->json(['message' => 'No commitments found'], 404);
    }


}