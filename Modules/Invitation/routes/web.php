<?php

use Illuminate\Support\Facades\Route;
use Modules\Invitation\Http\Controllers\InvitationController;
use Modules\Invitation\Http\Controllers\Web\WebInvitedUserController;
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
    Route::post('removeConnectedUser', [WebInvitedUserController::class, 'removeConnectedUsers'])
    ->name('v1.removeConnectedUser');
    Route::get('/invitation-method', [WebInvitedUserController::class, 'invitationMethodPage'])->name('invitation-method');
    Route::get('/invite-friends-family', [WebInvitedUserController::class, 'inviteFriendsAndFamilyPage'])->name('invite-friends-family');
    Route::prefix('v1')->group(function () {
//    Route::apiResource('invitation', InvitationController::class)->names('invitation');

        Route::middleware([\App\Http\Middleware\APICognito::class])->group(function () {
            Route::prefix('invitation')
                ->group(function () {

                    Route::post('sendEmails', [WebInvitedUserController::class, 'sendInviteByEmail'])
                        ->name('v1.invitation.send.email');

                });
        });

        Route::prefix('invitation')
            ->group(function () {

//                Route::post('openInvitation', [WebInvitedUserController::class, 'openInvitation'])
//                    ->name('v1.invitation.open.invitation');

                Route::middleware([\App\Http\Middleware\APICognito::class])->group(function () {

                    Route::post('acceptInvitation', [WebInvitedUserController::class, 'acceptInvitation'])
                        ->name('v1.invitation.accept.invitation');

                });

            });

    });

});

Route::prefix('v1')->group(function () {
//    Route::apiResource('invitation', InvitationController::class)->names('invitation');



    Route::prefix('invitation')
        ->group(function () {

            Route::post('openInvitation', [WebInvitedUserController::class, 'openInvitation'])
                ->name('v1.invitation.open.invitation');


            Route::post('rejectInvitation', [WebInvitedUserController::class, 'rejectInvitation'])
                ->name('v1.invitation.reject.invitation');

           

            Route::middleware([\App\Http\Middleware\APICognito::class])->group(function () {

                Route::post('acceptInvitation', [WebInvitedUserController::class, 'acceptInvitation'])
                    ->name('v1.invitation.accept.invitation');

                   

            });

        });

});
Route::middleware([\App\Http\Middleware\InertiaCognito::class])->group(function () {

    Route::get('/welcome-zyanaza', [WebInvitedUserController::class, 'welcomeZyanazaPage'])->name('welcomeZyanazaPage');

    Route::get('/investment-dashboard', [WebInvitedUserController::class, 'InvestmentDashboardPage'])->name('InvestmentDashboardPage');

});