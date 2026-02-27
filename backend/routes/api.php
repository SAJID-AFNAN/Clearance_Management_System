<?php

use Illuminate\Support\Facades\Route;

// Test route to check if API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});