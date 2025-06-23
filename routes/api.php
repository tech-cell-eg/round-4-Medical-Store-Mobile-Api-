<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// مسارات المنتجات (محمية بالمصادقة)
Route::middleware(['jwt.auth'])->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});

// مسارات المصادقة
Route::prefix('auth')->group(function () {
    Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('/register', 'App\Http\Controllers\Api\AuthController@register');
    
    // المسارات المحمية بالمصادقة
    Route::middleware(['jwt.auth'])->group(function () {
        Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
        Route::post('/refresh', 'App\Http\Controllers\Api\AuthController@refresh');
        Route::get('/user', 'App\Http\Controllers\Api\AuthController@user');
    });
});
