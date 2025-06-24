<?php

use Illuminate\Support\Facades\Route;
use Modules\Insurance\Http\Controllers\InsuranceController;
use Modules\Insurance\Http\Controllers\API\APIInsuranceController;

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
    Route::apiResource('insurance', InsuranceController::class)->names('insurance');
});

Route::middleware([\App\Http\Middleware\APICognito::class])->prefix('insurance')->group(function() {

    Route::post('update', [APIInsuranceController::class, 'storeInsurancePremiumDetails'])->name('api.insurance.premium.detail');
    Route::get('fetch', [APIInsuranceController::class, 'fetchInsurancePremiumDetails'])->name('api.insurance.premium.detail.fetch');

});


