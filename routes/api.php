<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestResetPasswordController;
use App\Http\Controllers\ResetPasswordController;

Route::controller(AuthController::class)->group(function() {
  Route::post('register', 'register');
  Route::post('login', 'login');
});

Route::post('request-reset', [RequestResetPasswordController::class, 'requestReset']);
Route::post('reset', [ResetPasswordController::class, 'resetPassword']);