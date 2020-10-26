<?php

use App\Http\Controllers\LectorController;

Route::prefix("lector")->group(function () {

    Route::post('nuevo-registro',[LectorController::class,"nuevoRegistroHuella"]);

    Route::post('nuevo-token', [LectorController::class, 'crearNuevoToken']);


});
