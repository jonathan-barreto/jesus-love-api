<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserDenominationController;
use App\Http\Controllers\UserInterestController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Auth routes
    Route::put('/auth/update', [AuthController::class, 'update']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::get('/auth/send-email-code-confirmation', [AuthController::class, 'sendEmailCodeConfirmation']);
    Route::post('/auth/verify-email-code', [AuthController::class, 'verifyEmailCode']);
    Route::post('/auth/update-email', [AuthController::class, 'updateEmail']);
    Route::get('/auth/logout', [AuthController::class, 'logout']);

    // Addresses routes
    Route::post('/addresses/create', [AddressController::class, 'create']);

    // Denomination routes
    Route::get('/denomination', [UserDenominationController::class, 'index']);
    Route::post('/denomination/create', [UserDenominationController::class, 'create']);

    // Interests routes
    Route::get('/interests', [UserInterestController::class, 'index']);
    Route::post('/interests/create', [UserInterestController::class, 'create']);

    // Photos routes
    Route::post('/photos/create', [PhotoController::class, 'create']);
    Route::delete('/photos/delete', [PhotoController::class, 'destroy']);

    // Match routes
    Route::post('/match/profiles', [MatchController::class, 'profiles']);
    Route::post('/match/like', [MatchController::class, 'like']);
});
