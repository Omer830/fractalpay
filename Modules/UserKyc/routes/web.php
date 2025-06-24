<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Modules\UserKyc\Http\Controllers\UserKycController;
use Modules\Auth\Http\Controllers\Web\WebAuthController;
use Modules\UserKyc\Http\Controllers\API\APIUserProfileController;
use Modules\UserKyc\Http\Controllers\Web\WebUserProfileController;
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


    Route::get('/steps', [WebAuthController::class, 'stepsPage'])->name('steps');
    
    Route::get('/doc-type', [WebUserProfileController::class, 'DocTypePage'])->name('doc-type');

    Route::get('/primary-documents', [WebUserProfileController::class, 'primaryDocumentPage'])->name('primary-documents');

    Route::get('/secondary-documents', [WebUserProfileController::class, 'SecondaryDocumentPage'])->name('secondary-documents');

    Route::get('/other-documents', [WebUserProfileController::class, 'otherDocumentPage'])->name('other-documents');


    Route::get('/profile', [WebUserProfileController::class, 'profilePage'])->name('profilePage');

    Route::get('/edit-profile', [WebUserProfileController::class, 'editProfilePage'])->name('editProfilePage');

    Route::post('/update-edit-profile', [WebUserProfileController::class, 'editProfileUpdatePage'])->name('editProfilePage');


    Route::get('/get-profile', [WebUserProfileController::class, 'getUserProfile'])->name('get-profile');

    Route::get('/kyc_status', [WebUserProfileController::class, 'getKycStatus'])->name('get-kyc-status');

    Route::post('/update-profile', [WebUserProfileController::class, 'updateUserProfileData'])->name('update-profile');

    Route::get('/welcome', [WebUserProfileController::class, 'showWelcomePage'])->name('welcome.page');

    Route::get('/success', [WebUserProfileController::class, 'showSuccessPage'])->name('success.page');


    Route::group([], function () {
        Route::resource('userkyc', UserKycController::class)->names('userkyc');
    });
});