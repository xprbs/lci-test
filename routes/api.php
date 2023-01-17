<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestResetPasswordController;

Route::controller(AuthController::class)->group(function() {
  Route::post('register', 'register');
  Route::post('login', 'login');
});

Route::post('request-reset', [RequestResetPasswordController::class, 'requestReset']);