<?php

use App\Http\Controllers\DiarioController;
use App\Http\Controllers\LectorController;

Route::prefix('admin')->group(function () {


    Route::post('nuevo-token-lector', [LectorController::class, 'crearNuevoToken']);
    Route::post('crear-diario', [DiarioController::class, 'crearProximoDiario']);

});
