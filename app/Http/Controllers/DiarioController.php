<?php

namespace App\Http\Controllers;

use App\Core\Services\DiariosService;
use App\Core\Tools\ApiMessage;
use App\Models\Diario;
use Illuminate\Http\Request;

class DiarioController extends Controller
{
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
