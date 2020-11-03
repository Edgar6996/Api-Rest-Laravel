<?php

use App\Http\Controllers\DiarioController;

Route::get('diario-actual', [DiarioController::class, 'showDiarioActual']);
Route::get('diario-actual/raciones-disponibles', [DiarioController::class, 'mostarRacionesDisponibles']);
Route::get('diario-actual/mi-reserva', [DiarioController::class, 'showReservaActual']);
Route::delete('diario-actual/reserva/{id_reserva}', [DiarioController::class, 'cancelarReserva']);
