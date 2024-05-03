<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/employee', [AuthController::class, 'employeeRegister']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout',   [AuthController::class, 'logout']);
});

Route::get('/product', [ProductController::class, 'index']);
Route::post('/product', [ProductController::class, 'store']);
Route::post('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);
Route::put('/product/{id}', [ProductController::class, 'disableProduct']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);
Route::post('/limitProduct/{id}', [ProductLimitController::class, 'getLimitByDate']);

Route::get('/category', [CategoryController::class, 'index']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

Route::get('/ingredient', [IngredientController::class, 'index']);

Route::get('/hampers', [HampersController::class, 'index']);
Route::post('/hampers', [HampersController::class, 'store']);
Route::delete('/hampers/{id}', [HampersController::class, 'destroy']);
Route::put('/hampers/{id}', [HampersController::class, 'disableHampers']);
Route::get('/hampers/{id}', [HampersController::class, 'getHampers']);
Route::post('/hampers/{id}', [HampersController::class, 'update']);

Route::get('/consignor', [ConsignorController::class, 'index']);

Route::get('/ingredientProcurement', [IngredientProcurementController::class, 'index']);
Route::get('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'getIngredientProcurement']);
