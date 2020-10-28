<?php

namespace App\Http\Controllers;

use App\Core\Services\DiariosService;
use App\Core\Tools\ApiMessage;
use App\Models\AppLogs;
use App\Models\Diario;
use Illuminate\Http\Request;

class DiarioController extends Controller
{
    public function showDiarioActual(Request $request )
    {
        $res = new ApiMessage($request);
        $diario_actual = Diario::diarioActual();
        if(!$diario_actual){
            # no hay diario actuak
            $service = new DiariosService();
            try {
                $diario_actual = $service->generarProximoDiario();
            } catch (\Exception $e) {
                AppLogs::addError("No fue posible crear el próximo diario.",$e);

                return $res->setCode(500)->setMessage("No hay un diario actual y no fué posible crearlo.")->send();
            }
        }

        $diario_actual->actualizarTotalRaciones();
        $res->setData($diario_actual);
        return $res->send();
    }
   public function mostarRacionesDisponibles(){
   		$res = new ApiMessage();

   		$diario_actual = Diario::diarioActual();
   		$diario_actual->actualizarTotalRaciones();

        $raciones_disponible = $diario_actual->calcularRacionesDisponibles();

        return $res->setData(['total' => $raciones_disponible])->send();
   }

	public function crearProximoDiario() {
		try {
             $diario = new DiariosService();
             return $diario->generarProximoDiario();
        } catch (\Exception $e){
            return $e->getMessage();
		}
	}
}
