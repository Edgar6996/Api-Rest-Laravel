<?php

use App\Http\Controllers\Auth\AuthController;

Route::post('auth/login', [AuthController::class, "login"]);
Route::get('auth/logout', [AuthController::class, "logout"]);
