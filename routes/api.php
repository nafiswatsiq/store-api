<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DeliveryCharge;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth', [UserController::class, 'checkAuth']);
Route::post('/register', [UserController::class, 'register']);


Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);

Route::get('/products/{id}', [ProductController::class, 'singgle']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'user']);   
    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/products', [ProductController::class, 'store']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::patch('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    Route::get('/address', [UserController::class, 'getAddress']);
    Route::post('/address', [UserController::class, 'addAddress']);
    Route::delete('/address/{id}', [UserController::class, 'deleteAddress']);

    Route::get('/likes', [LikeController::class, 'getLikes']);
    Route::post('/likes', [LikeController::class, 'addLikes']);

    Route::get('/delivery/province', [DeliveryCharge::class, 'getProvince']);
    Route::get('/delivery/province/{id}', [DeliveryCharge::class, 'getProvinceById']);
    Route::get('/delivery/province/{id}/city', [DeliveryCharge::class, 'getCity']);
    Route::get('/delivery/province/{id}/city/{city_id}', [DeliveryCharge::class, 'getCityById']);
    Route::post('/delivery/cost', [DeliveryCharge::class, 'getCost']);
    Route::get('/delivery/expedition', [DeliveryCharge::class, 'getExpedition']);
});
