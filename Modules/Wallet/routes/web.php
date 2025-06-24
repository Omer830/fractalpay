<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserProfileIsComplete;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Http\Controllers\Web\WebWalletController;
use Modules\Wallet\Http\Controllers\Web\WebCommitmentsController;
use Modules\Wallet\Http\Controllers\Web\WebTransactionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('wallet', WalletController::class)->names('wallet');
    Route::apiResource('wallet', WalletController::class)->names('wallet');

    Route::prefix('wallet')->group(function() {
        Route::post('createAccount', [WebWalletController::class, 'createAccount'])->name('v1.wallet.stripe.createAccount');
        Route::post('updateAccount', [WebWalletController::class, 'updateAccount'])->name('v1.wallet.stripe.updateAccount');
        Route::get('getAccount', [WebWalletController::class, 'getAccount'])->name('v1.wallet.stripe.account');
        Route::post('linkAccount', [WebWalletController::class, 'linkAccount'])->name('v1.wallet.stripe.linkAccount');
    });

    Route::prefix('transactions')->group(function() {
        Route::post('all', [WebWalletController::class, 'allTransactions'])->name('v1.transactions.all');
        Route::post('transfer', [WebWalletController::class, 'transferPayToUser'])->name('v1.transactions.create');
    });

});


Route::middleware([\App\Http\Middleware\InertiaCognito::class, EnsureUserProfileIsComplete::class])->group(function () {
    

Route::get('/dashboard', [WebWalletController::class, 'dashboardPage'])->name('dashboard');

//Route::get('/dashboard', [WebWalletController::class, 'userWallet'])->name('dashboard');

//Route::get('/transactions-history', [WebWalletController::class, 'transactionHistoryPage'])->name('route-transactions-history');

Route::get('/transactions-history', [WebTransactionController::class, 'allTransactions'])->name('transactions-history');

Route::get('/contribution-tree', [WebWalletController::class, 'contributionTreePage'])->name('contribution-tree');

Route::get('/contribution-detail', [WebCommitmentsController::class, 'allCommitments'])->name('contribution-detail');

Route::get('/contribution-invitation', [WebWalletController::class, 'contributionInvitationPage'])->name('contribution-invitation');

Route::get('/deposit-funds', [WebWalletController::class, 'DepositFundPage'])->name('deposit-funds');

Route::get('/share-funds', [WebWalletController::class, 'ShareFundsPage'])->name('share-funds');

Route::get('/bpay-summary', [WebWalletController::class, 'bpaySummaryPage'])->name('payment-summary');

Route::get('/fund-transfer-summary', [WebWalletController::class, 'FundTransferSummaryPage'])->name('fund-transfer-summary');

Route::post('/add-fund', [WebCommitmentsController::class, 'paySomeone'])->name('add-fund');

Route::post('/add-deposit', [WebCommitmentsController::class, 'addDeposit'])->name('add-fund');

Route::get('/friend-and-family', [WebCommitmentsController::class, 'frinedAndFamilyPage'])->name('add-fund');


Route::get('/contribution-individual-detail', [WebCommitmentsController::class, 'contributionDetailCardPage'])->name('contribution-individual-detail');

Route::post('/contribution-individual-detail', [WebCommitmentsController::class, 'getRecentTransaction'])->name('contribution-individual-detail');




Route::get('/contribution-summary', [WebCommitmentsController::class, 'contributionSummary'])->name('contribution-summary');

Route::post('/contribution-summary', [WebCommitmentsController::class, 'commitmentDetails'])->name('contribution-summary');

//Route::get('/payment-method', [WebWalletController::class, 'paymentMethodPage'])->name('payment-method');
});
//Route::group([], function () {
//    Route::resource('wallet', WalletController::class)->names('wallet');
//});
