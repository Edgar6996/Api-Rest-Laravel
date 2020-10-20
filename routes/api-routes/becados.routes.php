<?php

use App\Http\Controllers\BecadoControllers;


Route::get('/becados', [BecadoControllers::class, 'index']);                    // Listar
Route::post('/becados', [BecadoControllers::class, 'store']);                   // Crear becado
Route::put('/becados/{becado}', [BecadoControllers::class, 'update']);     // Actualizar
Route::get('/becados/{becado}', [BecadoControllers::class, 'show']);            // Listar becado individual

# Route::delete('/becados/{becado}', [BecadoControllers::class, 'destroy']);      // ELiminar becado

Route::delete('/becados/{becado}',[BecadoControllers::class, 'deshabilitarBecado']);
// Usamos post porque put no funcionaba en postamn
Route::post('/becados/{becado}/foto', [BecadoControllers::class, 'cargarFoto']);    // Cargar foto becado


# cargar-huella
# cargar-foto
