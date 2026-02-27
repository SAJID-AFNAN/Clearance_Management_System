<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Student routes
    Route::prefix('student')->middleware('checkrole:student')->group(function () {
        // We'll add these later
    });
    
    // Teacher routes
    Route::prefix('teacher')->middleware('checkrole:teacher')->group(function () {
        // We'll add these later
    });
    
    // Principal routes
    Route::prefix('principal')->middleware('checkrole:principal')->group(function () {
        // We'll add these later
    });
});