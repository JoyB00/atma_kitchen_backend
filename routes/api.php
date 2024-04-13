<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::get('/register/verify/{verify_key}', [App\Http\Controllers\Api\AuthController::class, 'verify']);

Route::get('/produks', [App\Http\Controllers\Api\ProdukController::class, 'index']);
Route::post('/produks', [App\Http\Controllers\Api\ProdukController::class, 'store']);
Route::patch('/produks/{id}', [App\Http\Controllers\Api\ProdukController::class, 'update']);
Route::delete('/produks/{id}', [App\Http\Controllers\Api\ProdukController::class, 'destroy']);
