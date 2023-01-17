<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RequestResetPasswordController;

Route::controller(AuthController::class)->group(function() {
  Route::post('register', 'register');
  Route::post('login', 'login');
});

Route::post('request-reset', [RequestResetPasswordController::class, 'requestReset']);
Route::post('reset', [ResetPasswordController::class, 'resetPassword']);

Route::apiResource('product', ProductController::class)->middleware('auth.verify');