<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login',[App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/produks', [App\Http\Controllers\api\ProdukController::class, 'index']);
