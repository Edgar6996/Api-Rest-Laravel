<?php

use App\Core\Services\DiariosService;
use App\Core\Tools\ApiMessage;
use App\Enums\CategoriasBecados;
use App\Http\Controllers\BecadoControllers;
use App\Models\Calendario;
use App\Models\AppConfig;
use App\Models\DetalleDiario;
use App\Models\Diario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

require_once "api-routes/auth.routes.php";
require_once "api-routes/lector.routes.php"; // Rutas exclusivas para el sistema Lector de huella


Route::middleware('auth:api')->get('/user', [BecadoControllers::class, 'usuarioActual']);

# Public routes
Route::get('/becados/report',[ BecadoControllers::class, 'exportListadoPDF']) // Generar un PDF con el listado
->name('report.becados');

# Private routes
Route::middleware('auth:api')->group(function () {
    // definir o importar rutas que deben usar autenticacion
    require_once "api-routes/becados.routes.php"; // Rutas becado requeridas
    require_once "api-routes/calendario.routes.php"; // Rutas calendario becado requeridas
    require_once "api-routes/diarios.routes.php";
    require_once "api-routes/config.routes.php";

    # Rutas exclusivas para administradores
    require_once "api-routes/admin.routes.php";
 });




### RUTAS TEMPORALES/DE PRUEBA ###


Route::get('prueba', function () {

    if (\App::isProduction()) {
        abort(404);
    }

    $service = new DiariosService();
    $service->procesarDiarios();



});
