<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout',   [App\Http\Controllers\Api\AuthController::class, 'logout']);
});


// Route::get('/register/verify/{verify_key}', [App\Http\Controllers\Api\AuthController::class, 'verify']);

Route::get('/products', [App\Http\Controllers\Api\ProdukController::class, 'index']);
Route::post('/products', [App\Http\Controllers\Api\ProdukController::class, 'store']);
Route::patch('/products/{id}', [App\Http\Controllers\Api\ProdukController::class, 'update']);
Route::delete('/products/{id}', [App\Http\Controllers\Api\ProdukController::class, 'destroy']);
Route::get('/products/{id}', [App\Http\Controllers\Api\ProdukController::class, 'getProduct']);
