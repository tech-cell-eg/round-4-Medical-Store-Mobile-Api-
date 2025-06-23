<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::controller(UserAuthController::class)->prefix("auth")->group(function () {
    Route::post("send-otp", "sendOtp");
    Route::post("verify-otp", "verifyOtp");
});

Route::middleware('auth:sanctum')->group(function () {
    // Notification Routes
    Route::controller(NotificationController::class)->prefix("notifications")->group(function () {
        Route::get("/", "index");                                  
        Route::patch("/{notificationId}/read", "markAsRead");
        Route::patch("/mark-all-read", "markAllAsRead");
        Route::delete("/{notificationId}", "destroy");
    });

});
