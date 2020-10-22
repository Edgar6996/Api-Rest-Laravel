<?php

use App\Http\Controllers\Auth\AuthController;

Route::post('auth/login', [AuthController::class, "login"]);
Route::middleware('auth:api')->get('auth/logout', [AuthController::class, "logout"]);
