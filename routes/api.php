<?php

use App\Core\Tools\ApiMessage;
use App\Http\Controllers\BecadoControllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

require_once "api-routes/auth.routes.php";
require_once "api-routes/lector.routes.php"; // Rutas exclusivas para el sistema Lector de huella
require_once "api-routes/becados.routes.php"; // Rutas becado requeridas
require_once "api-routes/calendario.routes.php"; // Rutas calendario becado requeridas
require_once "api-routes/admin.routes.php";

Route::middleware('auth:api')->get('/user', [BecadoControllers::class, 'usuarioActual']);

Route::get('admin/logs', [\App\Http\Controllers\AdminController::class,'indexAppLogs']);



### RUTAS TEMPORALES/DE PRUEBA ###

Route::get('users',[\App\Http\Controllers\Users\UsersController::class, "index"]);


Route::get('prueba', function () {

    $res = new ApiMessage();

    $res->setMessage("Ruta de prueba");

    return $res->send();


})->middleware('auth:api','user.lector');
