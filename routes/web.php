<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register/verify/{verify_key}', [App\Http\Controllers\Api\AuthController::class, 'verify']);
