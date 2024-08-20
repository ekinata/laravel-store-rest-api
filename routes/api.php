<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;

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

Route::get('/v1', function () {
    return response()->json(['message' => 'Hello World!']);
});

/**
 *  API Version 1
 */
Route::group(['prefix' => 'v1'], function () {
    
    /**
     * Guest Routes
     */
    Route::group(['middleware' => 'guest'], function () {
    
        /**
         * Auth Routes
         */
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
    
    /**
     * Authenticated Routes
     */
    Route::group(['middleware' => 'auth:sanctum'], function () {
    
        /**
         * Auth Routes
         */
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout/all', [AuthController::class, 'globalLogout']);
    });
});
