<?php

use App\Http\Controllers\CalendarioController;

# Grupo para las rutas protegidas
Route::middleware('auth:api')->prefix("calendario")->group(function () {
    // definir o importar rutas que deben usar autenticacion
    Route::get('{calendarioId}', [CalendarioController::class, 'show']);
    Route::put('{calendarioId}', [CalendarioController::class, 'update']);
 
 });
 
 Route::post('calendario/prueba', [CalendarioController::class, 'prueba']);