<?php

use Illuminate\Support\Facades\Route;
use Modules\Documents\Http\Controllers\Web\WebMediaController;
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



Route::post('/uploadKyc', [WebMediaController::class, 'uploadKyc'])
    ->name('uploadKyc');

Route::post('/profile', [WebMediaController::class, 'uploadProfileImage'])
    ->name('upload-profile-image');

Route::post('/edit-profile', [WebMediaController::class, 'uploadProfileImageEdit'])
        ->name('upload-profile-image');

Route::get('/document-upload', [WebMediaController::class, 'documentUploadPage'])->name('document-upload');

Route::delete('/delete-document/{media}', [WebMediaController::class, 'deleteDocuments'])->name('delete-document');


Route::middleware([\App\Http\Middleware\InertiaCognito::class])->group(function () {



    Route::post('/uploadKyc', [WebMediaController::class, 'uploadKyc'])
        ->name('uploadKyc');

    Route::post('/profile', [WebMediaController::class, 'uploadProfileImage'])
        ->name('upload-profile-image');

    Route::get('/document-upload', [WebMediaController::class, 'documentUploadPage'])->name('document-upload');

    Route::delete('/delete-document/{media}', [WebMediaController::class, 'deleteDocuments'])->name('delete-document');



    Route::group([], function () {

    });
});
});