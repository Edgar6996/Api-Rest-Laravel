<?php

use App\Http\Controllers\DiarioController;

Route::get('diario-actual', [DiarioController::class, 'showDiarioActual']);
Route::get('diario-actual/raciones-disponibles', [DiarioController::class, 'mostarRacionesDisponibles']);
Route::get('diario-actual/mi-reserva', [DiarioController::class, 'showReservaActual']);

Route::delete('diario-actual/reserva/{id_reserva}', [DiarioController::class, 'cancelarReserva']);

Route::get('diario-actual/mi-reserva/{becado_id}', [DiarioController::class, 'showReservaActualByBecadoId'])->middleware('user.lector'); ;

// Rutas sobre diarios solo para Admins
Route:: middleware('user.admin')->group(function () {
    Route::put('diario-actual/cargar-menu', [DiarioController::class, 'cargarMenu']);
    Route::put('diario-actual/aplicar-faltas', [DiarioController::class, 'changeAplicarFaltasToDiarioActual']);
});
