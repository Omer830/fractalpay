<?php

use Illuminate\Support\Facades\Route;
use Modules\Documents\Http\Controllers\API\APIMediaController;

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

    Route::prefix('documents')->group(function () {

        Route::get('', [APIMediaController::class, 'getDocuments'])
            ->name('api.documents.get');

        Route::post('/profileImage', [APIMediaController::class, 'uploadProfileImage'])
            ->name('api.documents.upload.media');

        Route::post('/uploadKyc', [APIMediaController::class, 'uploadKyc'])
            ->name('api.documents.upload.kyc');

        Route::delete('{media}', [APIMediaController::class, 'deleteDocuments'])
            ->name('api.documents.delete');

    });
});
