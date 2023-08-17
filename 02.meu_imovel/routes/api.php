<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginJwtController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStatePhotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('login', [LoginJwtController::class, 'login']);

    Route::middleware('auth:api')->group(function() {
        Route::name('users.')->group(function() {
            Route::resource('users', UserController::class);
        });

        Route::name('real_states.')->group(function() {
            Route::resource('real-states', RealStateController::class);
        });
    
        Route::name('categories.')->group(function() {
            Route::resource('categories', CategoryController::class);
            Route::get('categories/{id}/real-states', [CategoryController::class, 'realStates']);
        });
    
        Route::name('photos.')->group(function() {
            Route::put('photos/{photoId}/{realStateId}', [RealStatePhotoController::class, 'setThumb']);
            Route::delete('photos/{id}', [RealStatePhotoController::class, 'remove']);    
        });        
    });
});
