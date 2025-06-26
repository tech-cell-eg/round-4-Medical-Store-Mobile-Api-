<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UnitController;

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


Route::get('/products/advanced-search', [ProductController::class, "advancedSearch"]);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// مسارات المكونات (Ingredients)
Route::apiResource('ingredients', \App\Http\Controllers\Api\IngredientController::class);

// مسارات العبوات (Packages)
Route::apiResource('packages', \App\Http\Controllers\Api\PackageController::class);

// مسارات المنتجات (Products)
Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);

// مسارات وحدات القياس (Units)
Route::apiResource('units', \App\Http\Controllers\Api\UnitController::class);

// مسارات التقييمات (Reviews)
Route::apiResource('reviews', \App\Http\Controllers\Api\ReviewController::class);

// مسارات التصنيفات (Categories)
Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);

// مسارات وحدات القياس (Units)
Route::apiResource('units', \App\Http\Controllers\Api\UnitController::class);

// مسارات التقييمات (تحديث وحذف - تتطلب صلاحيات)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy'])->name('reviews.destroy');
});



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
