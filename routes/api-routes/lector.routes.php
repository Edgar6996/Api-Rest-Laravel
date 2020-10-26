<?php

use App\Http\Controllers\DiarioController;
use App\Http\Controllers\LectorController;

Route::prefix("lector")->group(function () {

    Route::post('nuevo-registro/{becado_id}',[LectorController::class,"nuevoRegistroHuella"]);

    Route::post('nuevo-token', [LectorController::class, 'crearNuevoToken']);


});

Route::get('diario-actual/raciones-disponibles', [DiarioController::class, 'mostarRacionesDisponibles']);
Route::post('crear-diario', [DiarioController::class, 'crearProximoDiario']);
