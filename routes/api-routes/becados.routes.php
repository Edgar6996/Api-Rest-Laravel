<?php

use App\Http\Controllers\BecadoControllers;


Route::get('/becados', [BecadoControllers::class, 'index'])->middleware('user.admin');                // Listar
Route::get('/becados-completo', [BecadoControllers::class, 'becadosCompleto'])->middleware('user.lector'); // Listar
Route::get('/becados-reserva', [BecadoControllers::class, 'becadosConReserva'])->middleware('user.admin'); // Listar
Route::put('/becados/{becado}', [BecadoControllers::class, 'update']);      // Actualizar
Route::get('/becados/{becado}', [BecadoControllers::class, 'show']);        // Listar becado individual

Route::get('/becados/report',[ BecadoControllers::class, 'exportListadoPDF']) // Generar un PDF con el listado
->name('report.becados');
Route::get('/becados/report-link', function () {
    $link =  URL::temporarySignedRoute('report.becados', now()->addHours(1));
    $res = new \App\Core\Tools\ApiMessage();
    return $res->setCode(200)->setData([
        'link' => $link
    ])->send();
})->middleware('user.admin');

# Route::delete('/becados/{becado}', [BecadoControllers::class, 'destroy']);  // ELiminar becado

// quitamos el middleware de esta ruta y lo  manejamos en el controller
Route::delete('/becados/{becado}',[BecadoControllers::class, 'deshabilitarBecado']);


// los Uploads los definimos con POST siempre.

Route::middleware('user.lector')->prefix("becados")->group( function(){

    Route::post('/', [BecadoControllers::class, 'store']);                               // Crear becado
    Route::post('{becado}/foto', [BecadoControllers::class, 'cargarFoto']);            // Cargar foto becado
    Route::post('{becado}/huella', [BecadoControllers::class, 'cargarHuella']);        // Cargar huella becado
    Route::get('{becado}/huellas', [BecadoControllers::class, 'showBecadoHuellas']);   // lista al becado con huella cargadas

});


