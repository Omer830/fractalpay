<?php

use Illuminate\Support\Facades\Route;
use Modules\Insurance\Http\Controllers\InsuranceController;
use Modules\Insurance\Http\Controllers\Web\WebInsuranceController;

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



    Route::get('/insurance', [WebInsuranceController::class, 'insuranceQuestionPage'])->name('insurance');

    Route::post('/store-insurance-detail', [WebInsuranceController::class, 'storeInsurancePremiumDetails'])->name('store-insurance-detail');


    Route::get('insurance', [WebInsuranceController::class, 'fetchInsurancePremiumDetails'])->name('api.insurance.premium.detail.fetch');



});


//Route::group([], function () {
//    Route::resource('insurance', InsuranceController::class)->names('insurance');
//});
