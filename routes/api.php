<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/employee', [App\Http\Controllers\Api\AuthController::class, 'employeeRegister']);
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
Route::post('/ingredient', [App\Http\Controllers\Api\IngredientController::class, 'store']);
Route::post('/ingredient/{id}', [App\Http\Controllers\Api\IngredientController::class, 'update']);
Route::delete('/ingredient/{id}', [App\Http\Controllers\Api\IngredientController::class, 'destroy']);
Route::get('/ingredient/{id}', [App\Http\Controllers\Api\IngredientController::class, 'getIngredient']);


Route::get('/hampers', [App\Http\Controllers\Api\HampersController::class, 'index']);
Route::post('/hampers', [App\Http\Controllers\Api\HampersController::class, 'store']);
Route::delete('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'destroy']);
Route::get('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'getHampers']);
Route::post('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'update']);
Route::get('/consignor', [App\Http\Controllers\Api\ConsignorController::class, 'index']);
Route::get('/ingredientProcurement', [App\Http\Controllers\Api\IngredientProcurementController::class, 'index']);
Route::get('/ingredientProcurement/{id}', [App\Http\Controllers\Api\IngredientProcurementController::class, 'getIngredientProcurement']);
