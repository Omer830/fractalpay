<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentMethod\Http\Controllers\API\APIUserPaymentMethodController;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;

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

Route::middleware([\App\Http\Middleware\APICognito::class, 'auth:sanctum'])->prefix('v1')->group(function () {


    Route::prefix('paymentMethod')->group(function () {

        Route::get('', [APIUserPaymentMethodController::class, 'getPaymentMethod'])
            ->name('api.v1.paymentMethod.index');

        Route::post('store', [APIUserPaymentMethodController::class, 'storePaymentMethod'])
            ->name('api.v1.paymentMethod.store');
        Route::post('delete', [APIUserPaymentMethodController::class, 'deletePaymentMethod'])
            ->name('api.v1.paymentMethod.delete');

        Route::get('createIntent', [APIUserPaymentMethodController::class, 'createIntent'])
            ->name('api.v1.paymentMethod.intent');

        Route::post('linkMethod', [APIUserPaymentMethodController::class, 'linkPaymentMethod'])
            ->name('api.v1.paymentMethod.link');
    });


});
