<?php

use App\Http\Controllers\BecadoControllers;


Route::get('/becados', [BecadoControllers::class, 'index']);                // Listar
Route::get('/becados-completo', [BecadoControllers::class, 'becadosCompleto'])->middleware('user.lector');             // Listar
Route::put('/becados/{becado}', [BecadoControllers::class, 'update']);      // Actualizar
Route::get('/becados/{becado}', [BecadoControllers::class, 'show']);        // Listar becado individual

# Route::delete('/becados/{becado}', [BecadoControllers::class, 'destroy']);  // ELiminar becado

Route::delete('/becados/{becado}',[BecadoControllers::class, 'deshabilitarBecado'])->middleware('user.admin');

// los Uploads los definimos con POST siempre.

Route::middleware('user.lector')->prefix("becados")->group( function(){

    Route::post('/', [BecadoControllers::class, 'store']);                               // Crear becado
    Route::post('{becado}/foto', [BecadoControllers::class, 'cargarFoto']);            // Cargar foto becado
    Route::post('{becado}/huella', [BecadoControllers::class, 'cargarHuella']);        // Cargar huella becado
    Route::get('{becado}/huellas', [BecadoControllers::class, 'showBecadoHuellas']);   // lista al becado con huella cargadas

});


