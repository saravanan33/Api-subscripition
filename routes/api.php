<?php

use App\Http\Controllers\ExternalApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

//Register New User
Route::post('/register', [UserController::class, 'register']);

//Login User With Valid credentials
Route::post('/login', [UserController::class, 'login']);

Route::group(['prefix' => '', 'middleware' => 'auth:sanctum'], function() {
    //To Get the user details
    Route::get('/userDetails', [UserController::class, 'getUserDetails']);
});


Route::middleware(['auth:sanctum', 'ratelimit'])->group(function () {

    // Test End point to apply rate limit logic
    Route::get('/data', [ExternalApiController::class, 'testApi']);
});