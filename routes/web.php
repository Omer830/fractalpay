<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Auth\Http\Controllers\Web\WebAuthController;

//Route::get('/', [HomeController::class, 'index']);


Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');

//Route::get('/', function () {
//    return Inertia::render('Login/Login');
//});
//
//
//Route::get('/signup', function () {
//    return Inertia::render('Signup');
//});
//
//Route::get('/forgot-password', function () {
//    return Inertia::render('ForgotPassword/ForgotPassword');
//});

//Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'index']);
//Route::get('/', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
//
//Route::get('/signup', [\App\Http\Controllers\Auth\LoginController::class, 'showSignUpForm'])->name('signup');
//
//Route::post('/register', [\App\Http\Controllers\Web\WebAuthController::class, 'register'])->name('user.register');

