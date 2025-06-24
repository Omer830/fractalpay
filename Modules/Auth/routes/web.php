<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Inertia\Inertia;
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

use Modules\Auth\Http\Controllers\Web\WebAuthController;



//Route::get('/', function () {
//        return Inertia::render('Login/Login');
//});


Route::get('/signup', function () {
    return Inertia::render('Signup');
});
//
Route::get('/forgot-password', function () {
    return Inertia::render('ForgotPassword/ForgotPassword');

});

Route::get('/choose-password', [WebAuthController::class, 'choosePasswordPage'])->name('choose-password');

Route::post('/choose-password', [WebAuthController::class, 'resetPassword'])->name('user.reset.password');
//Route::post('/', [WebAuthController::class, 'login']);
Route::get('/', [WebAuthController::class, 'showLoginForm']);

Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [WebAuthController::class, 'login'])->name('do.login');

Route::middleware([\App\Http\Middleware\InertiaCognito::class])->group(function () {
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('do.logout');
});
//


Route::get('/signup', [WebAuthController::class, 'showSignupForm'])->name('signup');
//
Route::post('/signup', [WebAuthController::class, 'register'])->name('signup');

Route::get('forgot-password/otp', function () {
    return Inertia::render('Otp/OtpFp/Otp');
})->name('forgot-password.otp');

Route::get('signup/otp', function () {
    return Inertia::render('Otp/Otp');
})->name('signup.otp');

Route::post('/verifyOTP', [WebAuthController::class, 'verifyOTP'])->name('verifyOTP');

Route::post('/verifyOTPFP', [WebAuthController::class, 'verifyOTPFP'])->name('verifyOTP');

Route::post('/sendOTP', [WebAuthController::class, 'sendOTP'])->name('sendOTP');

Route::get('/steps', [WebAuthController::class, 'stepsPage'])->name('steps');

Route::get('/welcome-invite', [WebAuthController::class, 'welcomeInvite'])->name('welcome-invite');


Route::post('/changePassword', [WebAuthController::class, 'changePassword'])->name('changeUserPassowrd');

Route::get('/terms-and-conditions', function () {
    return Inertia::render('TermsAndConditions/TermsAndConditions');
})->name('terms-and-conditions');

//
//
//
//Route::get('/forgot-password', [WebAuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
//
//Route::group([], function () {
//    Route::resource('auth', AuthController::class)->names('auth');
//});
