<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Student routes
    Route::prefix('student')->middleware('checkrole:student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard']);
        Route::post('/profile/complete', [StudentController::class, 'completeProfile']);
        Route::post('/clearance/submit', [StudentController::class, 'submitRequest']);
        Route::get('/clearance/{id}', [StudentController::class, 'requestStatus']);
        Route::get('/departments', [StudentController::class, 'departments']);
        Route::get('/halls', [StudentController::class, 'halls']);
    });

    // Teacher routes
    Route::prefix('teacher')->middleware('checkrole:teacher')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard']);
        Route::get('/student/{id}', [TeacherController::class, 'studentDetails']);
        Route::post('/approval/{id}/process', [TeacherController::class, 'processApproval']);
    });

    // Principal routes
    Route::prefix('principal')->middleware('checkrole:principal')->group(function () {
        // We'll add these later
    });
});
