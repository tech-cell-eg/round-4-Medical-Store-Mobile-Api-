<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::controller(UserAuthController::class)->prefix("auth")->group(function () {
    Route::post("send-otp", "sendOtp");
    Route::post("verify-otp", "verifyOtp");
});
