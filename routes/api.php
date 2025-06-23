<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

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

// مسارات وحدات القياس (Units)
Route::apiResource('units', \App\Http\Controllers\Api\UnitController::class);

// مسارات التقييمات (تحديث وحذف - تتطلب صلاحيات)
Route::middleware('auth:sanctum')->group(function () {
    Route::put('reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
"name": "AVOBAN",
        "description": "AVOBAN, AVOBAN Ointment is indicated for bacterial skinnfections",
        "production_date": "05-05-2024",
        "expiry_date": "20-12-2026",
        "brand_id": 1,
        "category_id": 1,
        "unit_id": 1,
        "image_url":"",
        "created_by":1,
        "updated_by":1
        */