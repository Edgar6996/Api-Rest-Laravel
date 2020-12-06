<?php

use App\Core\Tools\ApiMessage;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\LectorController;
use App\Http\Controllers\Users\UsersController;
use App\Models\Becado;

Route:: middleware('user.admin')->prefix('admin')->group(function () {

    Route::post('nuevo-token-lector', [LectorController::class, 'crearNuevoToken']);
    Route::post('crear-diario', [DiarioController::class, 'crearProximoDiario']);
    Route::post('reset-diario', [DiarioController::class, 'resetDiarioActual']);

    Route::get('users',[UsersController::class, "index"]);
    Route::post("users/nuevo",[UsersController::class, "registrarUsuario"]);

    Route::get('logs', [AdminController::class,'indexAppLogs']);

    Route::post('nuevo-registro/{becado_id}',[LectorController::class,"nuevoRegistroHuella"]);


    Route::get('check-becados', function () {
        # Cequeamos el estado de todos los becados
        $lista = Becado::all();
        $n = 0;
        foreach ($lista as $becado) {
            $becado->checkRegistroCompletado();
            $n++;
        }

        $res = new ApiMessage();
        $res->setMessage("Se han chequeado {$n} becados");
        return $res->send();
    });

});
