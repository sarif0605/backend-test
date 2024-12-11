<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ListItemController;
use App\Http\Controllers\API\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::prefix('v1')->group(function () {
    Route::apiResource('notes', NotesController::class);
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('verifikasi', [AuthController::class,'verifikasi'])->middleware('auth:api');
        Route::post('generate-otp-code', [AuthController::class,'generateOtpCode'])->middleware('auth:api');
    });
    Route::get('me', [AuthController::class, 'getUser'])->middleware('auth:api');
    Route::apiResource('list-item', ListItemController::class);
    Route::put('list-item/toggle-status', [ListItemController::class, 'toggleStatus']);
});
