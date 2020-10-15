<?php

use App\Http\Controllers\BecadoControllers;

Route::get('/becados', [BecadoControllers::class, 'index']);                    // Listar
Route::get('/becados/{becado}', [BecadoControllers::class, 'show']);            // Listar becado individual
Route::post('/becados', [BecadoControllers::class, 'store']);                   // Crear becado
Route::put('/becados/{becado}', [BecadoControllers::class, 'update']);     // Actualizar
Route::delete('/becados/{becado}', [BecadoControllers::class, 'destroy']);      // ELiminar becado
