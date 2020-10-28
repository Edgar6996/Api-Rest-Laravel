<?php

use App\Http\Controllers\CalendarioController;

Route::get('calendario/{calendarioId}', [CalendarioController::class, 'show']);
Route::put('calendario/{calendarioId}', [CalendarioController::class, 'update']);

Route::post('calendario/prueba', [CalendarioController::class, 'prueba']);


