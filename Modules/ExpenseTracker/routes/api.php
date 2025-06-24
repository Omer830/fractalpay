<?php

use Illuminate\Support\Facades\Route;
use Modules\ExpenseTracker\Http\Controllers\API\APIBillController;
use Modules\ExpenseTracker\Http\Controllers\API\APIVisitController;
use Modules\ExpenseTracker\Http\Controllers\ExpenseTrackerController;
use Modules\ExpenseTracker\Http\Controllers\API\APIExpensesController;

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
    Route::apiResource('expensetracker', ExpenseTrackerController::class)->names('expensetracker');

    Route::middleware([\App\Http\Middleware\APICognito::class])->group(function() {

        Route::controller(APIExpensesController::class)
            ->prefix('expense-tracker')
            ->group(function() {
                Route::get('stats', [APIExpensesController::class, 'stats'])->name('expense-tracker.stats');
                Route::post('getProviderDetails', [APIExpensesController::class, 'getProviderDetails']);
                Route::post('payingVisits', [APIExpensesController::class, 'getVisitAndBills'])->name('v1.payingBill');
//                Route::get('/getAllOrganizations', [APIExpensesController::class, 'getAllOrganizations']);
//                Route::get('/addVisitPage', [APIExpensesController::class, 'addVisitPage']);
        });

        Route::prefix('bill')
            ->group(function() {
                Route::post('', [APIBillController::class, 'storeBill'])->name('bill.store');
                Route::get('', [APIBillController::class, 'getBills'])->name('bill.getBills');
                Route::post('delete', [APIBillController::class, 'deleteBill'])->name('bill.delete');
                Route::get('{id}', [APIBillController::class, 'getBill'])->name('bill.getBill');
                Route::post('pay', [APIBillController::class, 'payBill'])->name('v1.payBill');
                Route::post('assign-contributors', [APIBillController::class, 'assignContributors'])->name('v1.assignContributors');
                Route::post('contributor', [APIBillController::class, 'getContributorBills']);
                Route::post('pendingBills', [APIBillController::class, 'pendingBills']);
            });

        Route::controller(APIVisitController::class)
            ->prefix('visit')
            ->group(function() {
                Route::get('', 'allVisits')->name('visit.all');
                Route::get('sharedVisit', 'getContributorVisit')->name('visit.shared');
                Route::post('deleteVisit', 'deleteVisit')->name('visit.delete');
                Route::post('', 'storeVisit')->name('visit.store');
                Route::patch('{visit}', 'UpdateVisit')->name('visit.update');
                Route::get('{visit}', 'getVisit')->name('visit.bills');
                Route::post('assign-contributors', 'assignContributors')->name('visit.assign-contributors');
        });
        
        Route::controller(APIExpensesController::class)
            ->prefix('notification')
            ->group(function() {
                Route::get('getUserNotification', 'getMyNotifications')->name('notification.all');
           
        });

    });


});

