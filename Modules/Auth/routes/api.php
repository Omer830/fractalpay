<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\API\APIAuthController;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\API\APIInsuranceController;

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

Route::post('/send_otp', [APIAuthController::class, 'sendOTP'])->name('user.send.verify_otp');
Route::middleware(\App\Http\Middleware\APICognito::class)->group(function () {


Route::post('/register', [APIAuthController::class, 'register'])->name('api.user.register');

Route::post('/login', [APIAuthController::class, 'login'])->name('user.login');

//Route::post('/send_otp', [APIAuthController::class, 'sendOTP'])->name('user.send.verify_otp');

Route::post('/verify_otp', [APIAuthController::class, 'verifyOTP'])->name('user.verify.register_otp');

Route::post('/refresh', [APIAuthController::class, 'refreshAccess'])->name('user.request.refresh_access');

Route::post('/forgot', [APIAuthController::class, 'forgot'])->name('user.forgot.password');

Route::post('/reset', [APIAuthController::class, 'resetPassword'])->name('user.reset.password');

Route::post('/insurance-premium-details', [APIInsuranceController::class, 'registerInsurancePremiumDetails'])->name('insurance.premium.details');




Route::prefix('auth')->middleware('auth')->group(function () {

//    Route::post('/send_otp', [APIAuthController::class, 'sendOTP'])->name('user.send.reset_otp');

    Route::post('/verify_otp', [APIAuthController::class, 'verifyOTP'])->name('user.verify.reset_otp');

    Route::get('/refresh', [APIAuthController::class, 'refreshAccess'])->name('user.request.auth_access');


});

Route::prefix('v1')->group(function () {
    Route::apiResource('auth', AuthController::class)->names('auth');
});

});
Route::middleware([\App\Http\Middleware\APICognito::class, 'auth:sanctum'])->group(function () {
    
    Route::post('/logout', [APIAuthController::class, 'logout'])->name('api.do.logout');

    Route::post('/changePassword', [APIAuthController::class, 'changePassword'])->name('changeUserPassowrd');

    Route::post('/addCustomAttributeAWS', [APIAuthController::class, 'initializeCustomAttributes'])->name('addCustomAttributeAWS');

    Route::post('/updateEmailAttribute', [APIAuthController::class, 'updateAlternativeSecondaryEmail'])->name('updateEmailAttribute');

    Route::post('/verifyCustomEmail', [APIAuthController::class, 'verifyCustomEmail'])->name('verifyCustomEmail');


});
