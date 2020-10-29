<?php

use App\Http\Controllers\DiarioController;
use App\Http\Controllers\LectorController;

Route:: middleware('user.admin')->prefix('admin')->group(function () {

    Route::post('nuevo-token-lector', [LectorController::class, 'crearNuevoToken']);
    Route::post('crear-diario', [DiarioController::class, 'crearProximoDiario']);

    Route::get('users',[\App\Http\Controllers\Users\UsersController::class, "index"]);


    Route::get('logs', [\App\Http\Controllers\AdminController::class,'indexAppLogs']);


    Route::get('check-becados', function () {
        # Cequeamos el estado de todos los becados
        $lista = \App\Models\Becado::all();
        $n = 0;
        foreach ($lista as $becado) {
            $becado->checkRegistroCompletado();
            $n++;
        }

        $res = new \App\Core\Tools\ApiMessage();
        $res->setMessage("Se han chequeado {$n} becados");
        return $res->send();
    });
});
