<?php

use Illuminate\Support\Facades\Route;
use Modules\UserKyc\Http\Controllers\API\APIUserProfileController;
use Modules\UserKyc\Http\Controllers\UserKycController;

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

Route::middleware([\App\Http\Middleware\APICognito::class])->group(function () {

    Route::prefix('profile')->group(function () {

        Route::get('/', [APIUserProfileController::class, 'getUserProfile']);

        Route::get('/documents', [APIUserProfileController::class, 'getUserDocuments']);

        Route::get('kyc_status', [APIUserProfileController::class, 'getKycStatus'])
            ->name('api.documents.kyc.status');

        Route::post('/update', [APIUserProfileController::class, 'updateUserProfileData']);
    });
});
