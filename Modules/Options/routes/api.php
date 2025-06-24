<?php

use Illuminate\Support\Facades\Route;
use Modules\Options\Http\Controllers\OptionsController;

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
    Route::apiResource('options', OptionsController::class)->names('options');
});


Route::middleware(['auth:sanctum', \App\Http\Middleware\APICognito::class])->group(function () {

    Route::prefix('options')->group(function() {

        Route::get('/', [\Modules\Options\Http\Controllers\API\APIOptionListController::class, 'getList']);
        Route::get('/categories', [\Modules\Options\Http\Controllers\API\APIOptionListController::class, 'getCategories']);

    });

});