<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/employee', [AuthController::class, 'employeeRegister']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verifyEmail', [AuthController::class, 'verifyEmail']);
Route::post('/verifyCode', [AuthController::class, 'verifyCode']);
Route::middleware('auth:api')->group(function () {
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::post('/ingredientProcurement', [IngredientProcurementController::class, 'store']);
    Route::post('/otherProcurement', [OtherProcurementsController::class, 'store']);
});


// Customer
Route::get('/customer', [CustomerController::class, 'index']);

// Transaksi
Route::get('/orderHistory/{id}', [TransactionController::class, 'getOrderHistory']);
Route::get('/detailOrder/{id}', [TransactionController::class, 'getDetailOrder']);

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
Route::post('/ingredient', [IngredientController::class, 'store']);
Route::post('/ingredient/{id}', [IngredientController::class, 'update']);
// Route::delete('/ingredient/{id}', [IngredientController::class, 'destroy']);
Route::delete('/ingredient/{id}', [IngredientController::class, 'disableIngredient']);
Route::get('/ingredient/{id}', [IngredientController::class, 'getIngredient']);

Route::get('/hampers', [HampersController::class, 'index']);
Route::post('/hampers', [HampersController::class, 'store']);
Route::delete('/hampers/{id}', [HampersController::class, 'destroy']);
Route::put('/hampers/{id}', [HampersController::class, 'disableHampers']);
Route::get('/hampers/{id}', [HampersController::class, 'getHampers']);
Route::post('/hampers/{id}', [HampersController::class, 'update']);

Route::get('/consignor', [ConsignorController::class, 'index']);
Route::get('/consignor/{id}', [ConsignorController::class, 'getConsignor']);
Route::post('/consignor', [ConsignorController::class, 'store']);
Route::put('/consignor/{id}', [ConsignorController::class, 'update']);
Route::delete('/consignor/{id}', [ConsignorController::class, 'disableConsignor']);

Route::get('/ingredientProcurement', [IngredientProcurementController::class, 'index']);
Route::get('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'getIngredientProcurement']);
Route::put('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'update']);
Route::delete('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'destroy']);

// Other Procurement
Route::get('/otherProcurement', [OtherProcurementsController::class, 'index']);
Route::get('/otherProcurement/{id}', [OtherProcurementsController::class, 'getProcurement']);
Route::put('/otherProcurement/{id}', [OtherProcurementsController::class, 'update']);
Route::delete('/otherProcurement/{id}', [OtherProcurementsController::class, 'destroy']);


// Employee
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::put('/employee/{id}', [EmployeeController::class, 'update']);
Route::delete('employee/{id}', [EmployeeController::class, 'deactivate']);
Route::delete('employee/reactivate/{id}', [EmployeeController::class, 'reactivate']);

// Salary
Route::get('/salaryEmployee/{id}', [SalariesController::class, 'getDetailSalary']);

// Role
Route::get('/role', [RoleController::class, 'index']);
Route::post('/role', [RoleController::class, 'store']);
Route::put('/role/{id}', [RoleController::class, 'update']);
Route::delete('/role/{id}', [RoleController::class, 'destroy']);
Route::get('/role/{id}', [RoleController::class, 'show']);
