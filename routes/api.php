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

Route::middleware('auth:api')->get('/user', [BecadoControllers::class, 'usuarioActual']);

Route::get('admin/logs', [\App\Http\Controllers\AdminController::class,'indexAppLogs']);



### RUTAS TEMPORALES/DE PRUEBA ###

Route::get('users',[\App\Http\Controllers\Users\UsersController::class, "index"]);

Route::post('prueba',[\App\Http\Controllers\EjemploController::class,"storeBlob"]);
Route::get('prueba', function () {

    $res = new ApiMessage();


    $user = \App\Models\User::first();
    $a = Passport::$personalAccessTokensExpireAt->format('c');

    $b = Passport::$personalAccessTokensExpireAt->format('c');
    $token = $user->createToken('token_1');
    dd([
        'a' => $a,
        'b' => $b
    ]);

    #$p = Token::query()->where('name','like','token_1')->get();
//    $p =  $user->tokens()->get();
//    $p[0]->revoke();
//    dd($p);
    return $res->send();


});
