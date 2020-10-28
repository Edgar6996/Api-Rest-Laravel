<?php

use App\Http\Controllers\DiarioController;

Route::get('diario-actual', [DiarioController::class, 'showDiarioActual']);
Route::get('diario-actual/raciones-disponibles', [DiarioController::class, 'mostarRacionesDisponibles']);
