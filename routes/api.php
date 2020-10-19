<?php

use App\Core\Tools\ApiMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require_once "api-routes/auth.routes.php";
require_once "api-routes/becados.routes.php"; // Rutas becado requeridas
require_once "api-routes/calendario.routes.php"; // Rutas calendario becado requeridas


Route::middleware('auth:api')->get('/user', function (Request $request) {

    # Obtener el usuario:
    $usuarioActual = Auth::user();

    $idUsuarioActual = Auth::id();

    # Saber si el usuario actual esta logeado
    $esInvitado =  Auth::guest();

    $res = new ApiMessage();

    $res->setData([
        'invitado' => $esInvitado
    ]);

    return $res->send();
});









Route::post('prueba',[\App\Http\Controllers\EjemploController::class,"storeBlob"]);
Route::get('prueba', function () {

    $res = new ApiMessage();

    # Saber si el usuario actual esta logeado
    $esInvitado =  Auth::guest();

    $res = new ApiMessage();

    $res->setData([
        'invitado' => $esInvitado
    ]);

    return $res->send();


});
