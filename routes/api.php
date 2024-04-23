<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout',   [App\Http\Controllers\Api\AuthController::class, 'logout']);
});


// Route::get('/register/verify/{verify_key}', [App\Http\Controllers\Api\AuthController::class, 'verify']);

Route::get('/product', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::post('/product', [App\Http\Controllers\Api\ProductController::class, 'store']);
Route::post('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'update']);
Route::delete('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'destroy']);
Route::get('/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'getProduct']);
Route::get('/category', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::delete('/category/{id}', [App\Http\Controllers\Api\CategoryController::class, 'destroy']);
Route::get('/ingredient', [App\Http\Controllers\Api\IngredientController::class, 'index']);
Route::get('/hampers', [App\Http\Controllers\Api\HampersController::class, 'index']);
