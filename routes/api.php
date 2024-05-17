<?php

namespace App\Http\Controllers\api;

use App\Http\Middleware\UserRoleCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/employee', [AuthController::class, 'employeeRegister']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verifyEmail', [AuthController::class, 'verifyEmail']);
Route::post('/verifyCode', [AuthController::class, 'verifyCode']);

// Product
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);

// Hampers
Route::get('/hampers', [HampersController::class, 'index']);
Route::get('/hampers/{id}', [HampersController::class, 'getHampers']);

// Category
Route::get('/category', [CategoryController::class, 'index']);

Route::middleware('auth:api')->group(function () { // all logged in user
    Route::post('/changePassword', [AuthController::class, 'changePassword']);
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::get('/showUser', [AuthController::class, 'getUserByToken']);

    // Customer
    Route::get('/customer', [CustomerController::class, 'index']);
    Route::get('/customer/{id}', [CustomerController::class, 'show']);
    Route::get('/customerLoggedIn', [CustomerController::class, 'showLoggedIn']);

    // Cart
    Route::get('/cart', [CartsController::class, 'index']);
    Route::get('/cartPerDate', [CartsController::class, 'showCartPerDate']);

    // Order
    Route::get('/orderHistory/{id}', [TransactionController::class, 'getOrderHistory']);
    Route::get('/detailOrder/{id}', [TransactionController::class, 'getDetailOrder']);
    Route::get('/searchProductNameInTransactions/{term}', [TransactionController::class, 'searchProductNameInTransactions']);

    // Ingredient
    Route::get('/ingredient', [IngredientController::class, 'index']);
    Route::get('/ingredient/{id}', [IngredientController::class, 'getIngredient']);

    // Consignor
    Route::get('/consignor', [ConsignorController::class, 'index']);
    Route::get('/consignor/{id}', [ConsignorController::class, 'getConsignor']);

    // Ingredient Procurement
    Route::get('/ingredientProcurement', [IngredientProcurementController::class, 'index']);
    Route::get('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'getIngredientProcurement']);

    // Other Procurement
    Route::get('/otherProcurement', [OtherProcurementsController::class, 'index']);
    Route::get('/otherProcurement/{id}', [OtherProcurementsController::class, 'getProcurement']);

    // Employee
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::get('/employeeForSalary', [EmployeeController::class, 'showEmployee']);
    Route::get('/employee/{id}', [EmployeeController::class, 'show']);
    Route::post('/changePasswordEmployee', [EmployeeController::class, 'changePasswordEmployee']);

    // Salary
    Route::get('/employeeSalary', [SalariesController::class, 'index']);
    Route::get('/salary/{id}', [SalariesController::class, 'getSalary']);
    Route::get('/employeeSalary/{id}', [SalariesController::class, 'getDetailSalary']);

    // Role
    Route::get('/role', [RoleController::class, 'index']);
    Route::get('/role/{id}', [RoleController::class, 'show']);

    // Absence
    Route::get('/absence', [AbsenceController::class, 'index']);
    Route::get('/absence/{id}', [AbsenceController::class, 'show']);

    // Address
    Route::get('/address/{id}', [AddressController::class, 'show']);
});

Route::middleware(['auth:api', UserRoleCheck::class . ':1'])->group(function () { // logged in and have Owner role
    Route::post('/employeeSalary', [SalariesController::class, 'store']);
    Route::put('/employeeSalary/{id}', [SalariesController::class, 'update']);
    Route::delete('/employeeSalary/{id}', [SalariesController::class, 'destroy']);
});

Route::middleware(['auth:api', UserRoleCheck::class . ':2'])->group(function () { // logged in and have Admin role
    // Ingredient
    Route::post('/ingredient', [IngredientController::class, 'store']);
    Route::post('/ingredient/{id}', [IngredientController::class, 'update']);
    Route::put('/ingredient/{id}', [IngredientController::class, 'disableIngredient']);
    Route::delete('/ingredient/{id}', [IngredientController::class, 'destroy']);

    // Hampers
    Route::post('/hampers', [HampersController::class, 'store']);
    Route::put('/hampers/{id}', [HampersController::class, 'disableHampers']);
    Route::post('/hampers/{id}', [HampersController::class, 'update']);
    Route::delete('/hampers/{id}', [HampersController::class, 'destroy']);

    // Product
    Route::post('/product', [ProductController::class, 'store']);
    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::put('/product/{id}', [ProductController::class, 'disableProduct']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
    Route::post('/limitProduct/{id}', [ProductLimitController::class, 'getLimitByDate']);

    // Category
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
});

Route::middleware(['auth:api', UserRoleCheck::class . ':3'])->group(function () { // logged in and have MO role
    // Employee
    Route::put('/employee/{id}', [EmployeeController::class, 'update']);
    Route::delete('employee/{id}', [EmployeeController::class, 'deactivate']);
    Route::delete('employee/reactivate/{id}', [EmployeeController::class, 'reactivate']);

    // Consignor
    Route::post('/consignor', [ConsignorController::class, 'store']);
    Route::put('/consignor/{id}', [ConsignorController::class, 'update']);
    Route::put('/disableConsignor/{id}', [ConsignorController::class, 'disableConsignor']);

    // Ingredient Procurement
    Route::post('/ingredientProcurement', [IngredientProcurementController::class, 'store']);
    Route::put('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'update']);
    Route::delete('/ingredientProcurement/{id}', [IngredientProcurementController::class, 'destroy']);

    // Other Procurement
    Route::post('/otherProcurement', [OtherProcurementsController::class, 'store']);
    Route::put('/otherProcurement/{id}', [OtherProcurementsController::class, 'update']);
    Route::delete('/otherProcurement/{id}', [OtherProcurementsController::class, 'destroy']);

    // Absence
    Route::post('/absence', [AbsenceController::class, 'store']);
    Route::put('/absence/{id}', [AbsenceController::class, 'update']);
    Route::delete('/absence/{id}', [AbsenceController::class, 'destroy']);

    // Role
    Route::post('/role', [RoleController::class, 'store']);
    Route::put('/role/{id}', [RoleController::class, 'update']);
    Route::delete('/role/{id}', [RoleController::class, 'destroy']);
});

Route::middleware(['auth:api', UserRoleCheck::class . ':4'])->group(function () { // logged in and have Customer role
    // Profile management
    Route::put('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);

    // cart
    Route::post('/cart', [CartsController::class, 'store']);
    Route::put('/cart/{id}', [CartsController::class, 'update']);
    Route::delete('/cart/{id}', [CartsController::class, 'destroy']);
    // Address
    Route::get('/address', [AddressController::class, 'index']);
    Route::post('/address', [AddressController::class, 'store']);
    Route::put('/address/{id}', [AddressController::class, 'update']);
    Route::delete('/address/{id}', [AddressController::class, 'destroy']);
});
