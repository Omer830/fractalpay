<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserProfileIsComplete;
use Modules\ExpenseTracker\Http\Controllers\Web\WebBillController;
use Modules\ExpenseTracker\Http\Controllers\Web\WebVisitController;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;
use Modules\ExpenseTracker\Http\Controllers\Web\WebExpensesController;

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
Route::middleware([\App\Http\Middleware\InertiaCognito::class, EnsureUserProfileIsComplete::class,])->group(function () {

    

Route::get('/expense-tracker', [WebExpensesController::class, 'showExpenseTrackerPage'])->name('expense-tracker');
Route::get('/add-manual-bills', [WebVisitController::class, 'showAddManualBillPage'])->name('add-bills');
Route::get('/add-contributors', [WebExpensesController::class, 'showAddContributorsPage'])->name('add-bills');
Route::get('/total-payable', [WebExpensesController::class, 'showTotalPayablePage'])->name('payable.page');
Route::get('/payment-bill-summary', [WebVisitController::class, 'getBillsAgainstVisit'])->name('payment-bill-summary');
Route::post('/payment-bill-summary', [WebVisitController::class, 'getBillsAgainstVisitList'])->name('payment-bill-summary');
Route::get('/add-bill', [WebExpensesController::class, 'showAddBillPage'])->name('add-bill');

Route::post('/add-bill', [WebBillController::class, 'payBill'])->name('add-bill');

Route::post('/delete-bill', [WebBillController::class, 'deleteBill'])->name('delete-bill');

Route::post('/add-visit', [WebVisitController::class, 'storeVisit'])->name('add.visit');

Route::post('/delete-visit', [WebBillController::class, 'deleteVisit'])->name('delete-bill');

Route::post('/expense-tracker', [WebExpensesController::class, 'getProviderDetails'])->name('expense.tracker');

Route::post('/store-bill', [WebBillController::class, 'storeBill'])->name('store.bill');

Route::post('/store-bill', [WebBillController::class, 'storeBill'])->name('store.bill');

    Route::post('/add-contributors', [WebBillController::class, 'assignContributors'])->name('add.contributors');
    Route::post('/add-contributors-bill', [WebBillController::class, 'assignContributorsToBill'])->name('add.contributors');

    Route::controller(WebExpensesController::class)
        ->prefix('expense-tracker')
        ->group(function() {
            Route::get('stats', [WebExpensesController::class, 'stats'])->name('expense-tracker.stats');
            Route::post('getProviderDetails', [WebExpensesController::class, 'getProviderDetails']);
            Route::post('payingVisits', [WebExpensesController::class, 'getVisitAndBills'])->name('v1.payingBill');
//                Route::get('/getAllOrganizations', [APIExpensesController::class, 'getAllOrganizations']);
//                Route::get('/addVisitPage', [APIExpensesController::class, 'addVisitPage']);
        });
    Route::get('/getUserNotification', [WebExpensesController::class, 'getMyNotifications'])->name('notification.all');
//Route::post('/get-provider-detail', [WebExpensesController::class, 'getProviderDetails'])->name('add.visit');





});


//Route::group([], function () {
//    Route::resource('expensetracker', ExpenseTrackerController::class)->names('expensetracker');
//});
