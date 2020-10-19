<?php

use App\Http\Controllers\CalendarioController;

Route::get('calendario/{becadoId}', [CalendarioController::class, 'show']);
Route::put('calendario/{becadoId}', [CalendarioController::class, 'update']);