<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;
use Modules\PaymentMethod\Http\Controllers\Web\WebUserPaymentMethodController;

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

Route::middleware([\App\Http\Middleware\InertiaCognito::class])->group(function () {






       Route::get('/payment-method', [WebUserPaymentMethodController::class, 'getPaymentMethod'])
       ->name('payment-method');

//    Route::get('/payment-method', [WebUserPaymentMethodController::class, 'getPaymentMethod'])
//        ->name('payment-method');

    Route::post('/store-payment-method', [WebUserPaymentMethodController::class, 'storePaymentMethod'])
        ->name('store-payment-method');

    Route::post('delete-payment-method', [WebUserPaymentMethodController::class, 'deletePaymentMethod'])
        ->name('api.v1.paymentMethod.delete');

});
