<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\API\APICommitmentsController;
use Modules\Wallet\Http\Controllers\API\APITransactionController;
use Modules\Wallet\Http\Controllers\API\APIWalletController;
use Modules\Wallet\Http\Controllers\WalletController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('wallet', WalletController::class)->names('wallet');

    Route::prefix('wallet')->group(function() {
        Route::get('', [APIWalletController::class, 'userWallet'])->name('v1.wallet.userWallet');
        Route::post('createAccount', [APIWalletController::class, 'createAccount'])->name('v1.wallet.stripe.createAccount');
        Route::post('updateAccount', [APIWalletController::class, 'updateAccount'])->name('v1.wallet.stripe.updateAccount');
        Route::get('getAccount', [APIWalletController::class, 'getAccount'])->name('v1.wallet.stripe.account');
        Route::post('linkAccount', [APIWalletController::class, 'linkAccount'])->name('v1.wallet.stripe.linkAccount');
    });

    Route::prefix('commitments')->group(function() {
        Route::post('paySomeone', [APICommitmentsController::class, 'paySomeone'])->name('v1.commitments.paySomeone');
        Route::post('lastCommitment', [APICommitmentsController::class, 'lastCommitment'])->name('v1.commitments.lastCommitment');
        Route::post('addDeposit', [APICommitmentsController::class, 'addDeposit'])->name('v1.commitments.addDeposit');
        Route::post('recentTransaction', [APICommitmentsController::class, 'getRecentTransaction'])->name('v1.commitments.Recent_Transaction');
        Route::post('getCommitmentDetails', [APICommitmentsController::class, 'commitmentDetails'])->name('v1.commitments.Details');
        Route::get('all', [APICommitmentsController::class, 'allCommitments'])->name('v1.commitments.all');
    });

    Route::prefix('transactions')->group(function() {
        Route::post('all', [APITransactionController::class, 'allTransactions'])->name('v1.transactions.all');
        Route::post('history', [APIWalletController::class, 'walletHistory'])->name('v1.transactions.history');
        Route::post('latestBills', [APIWalletController::class, 'latestBillPaid'])->name('v1.transactions.latest.bills');
        Route::post('transfer', [APITransactionController::class, 'transferPayToUser'])->name('v1.transactions.create');
        Route::patch('{transaction}', [APITransactionController::class, 'updateStatus'])->name('v1.transactions.update.status');
    });

});


