<?php

use App\Http\Controllers\LectorController;
# Al siguiente grupo, solo tiene acceso el Sistema Lector
Route::middleware(['auth:api','user.lector'])->prefix("lector")->group(function () {

    Route::post('nuevo-registro/{becado_id}',[LectorController::class,"nuevoRegistroHuella"]);




});

