<?php

use App\Http\Controllers\BecadoControllers;


Route::get('/becados', [BecadoControllers::class, 'index']);                    // Listar
Route::post('/becados', [BecadoControllers::class, 'store']);                   // Crear becado
Route::put('/becados/{becado}', [BecadoControllers::class, 'update']);     // Actualizar
Route::get('/becados/{becado}', [BecadoControllers::class, 'show']);            // Listar becado individual

# Route::delete('/becados/{becado}', [BecadoControllers::class, 'destroy']);      // ELiminar becado

Route::delete('/becados/{becado}',[BecadoControllers::class, 'deshabilitarBecado']);

// los Uploads los definimos con POST siempre.
Route::post('/becados/{becado}/foto', [BecadoControllers::class, 'cargarFoto']);    // Cargar foto becado
Route::post('/becados/{becado}/huella', [BecadoControllers::class, 'cargarHuella']); // Cargar huella becado
Route::get('/becados/{becado}/huellas', [BecadoControllers::class, 'showBecadoHuellas']); // Cargar huella becado

