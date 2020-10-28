<?php

use App\Http\Controllers\DiarioController;
use App\Http\Controllers\LectorController;

Route:: middleware('user.admin')->prefix('admin')->group(function () {

    Route::post('nuevo-token-lector', [LectorController::class, 'crearNuevoToken']);
    Route::post('crear-diario', [DiarioController::class, 'crearProximoDiario']);

    Route::get('users',[\App\Http\Controllers\Users\UsersController::class, "index"]);

    Route::get('logs', [\App\Http\Controllers\AdminController::class,'indexAppLogs']);
});