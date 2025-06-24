<?php

use Illuminate\Support\Facades\Route;
use Modules\Invitation\Http\Controllers\API\APIInvitedUserController;
use Modules\Invitation\Http\Controllers\InvitationController;

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

Route::prefix('v1')->group(function () {
//    Route::apiResource('invitation', InvitationController::class)->names('invitation');

    Route::middleware([\App\Http\Middleware\APICognito::class])->group(function() {
        Route::prefix('invitation')
            ->group(function() {

                Route::post('sendEmails', [APIInvitedUserController::class, 'sendInviteByEmail'])
                    ->name('v1.invitation.send.email');

            });
    });

    Route::prefix('invitation')
        ->group(function() {

            Route::post('openInvitation', [APIInvitedUserController::class, 'openInvitation'])
                ->name('v1.invitation.open.invitation');


            Route::post('rejectInvitation', [APIInvitedUserController::class, 'rejectInvitation'])
                ->name('v1.invitation.reject.by-code');

            Route::middleware([\App\Http\Middleware\APICognito::class, 'auth:sanctum'])->group(function () {

                Route::post('acceptInvitation/{invitation?}', [APIInvitedUserController::class, 'acceptInvitation'])
                    ->name('v1.invitation.accept.invitation');

                Route::post('rejectInvitation/{invitation}', [APIInvitedUserController::class, 'rejectInvitation'])
                    ->name('v1.invitation.reject.by-id');

                Route::get('getInvitation', [APIInvitedUserController::class, 'getInvitationList'])
                    ->name('v1.invitation.getInvitation');

                Route::get('connectedUsers', [APIInvitedUserController::class, 'getConnectedUsers'])
                    ->name('v1.invitation.getConnectedUsers');

                Route::post('removeConnectedUser', [APIInvitedUserController::class, 'removeConnectedUsers'])
                    ->name('v1.invitation.removeConnectedUsers');

            });

        });

});




