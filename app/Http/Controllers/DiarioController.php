<?php

namespace App\Http\Controllers;

use App\Core\Services\DiariosService;
use App\Core\Tools\ApiMessage;
use Carbon\Carbon;
use App\Models\AppLogs;
use App\Models\Becado;
use App\Models\DetalleDiario;
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

   public function showReservaActual(Request $request)
   {
       $res = new ApiMessage();
       $becado = Becado::getBecadoActual();
       $diarioActual = Diario::diarioActual();

       if (!$becado) {
          return $res->setCode(404)->setMessage('El usuario actual no es becado')->send();
       }

       $reserva = $diarioActual->detalleDiario()->where('becado_id', $becado->id)->first();

       if (!$reserva) {
            return $res->setCode(404)->setMessage('El usuario actual no tiene una reserva')->send();
       }

       return $res->setData($reserva)->send();
   }

	public function crearProximoDiario() {
		try {
             $service = new DiariosService();
             $service->procesarDiarios();

             return Diario::diarioActual();
        } catch (\Exception $e){
            return $e->getMessage();
		}
    }

    public function resetDiarioActual()
    {
        $res = new ApiMessage();

        $service = new DiariosService();
        $ok = $service->resetDiarioActual();
        if(!$ok){
            return $res->setCode(409)->setMessage("No fué posible resetear el diario actual")->send();
        }

        $res->setMessage("Diario reseteado");
        $diario = Diario::diarioActual();
        $res->setData($diario);

        return $res->send();
    }

    /**
     * Cancela una reserva DEL DIARIO ACTUAL
     */
    public function cancelarReserva($id_reserva){
        $res = new ApiMessage();
        $diario = Diario::diarioActual();
        $hora_limite = $diario->horaLimite();
        $hora_actual = Carbon::now();

        if($hora_actual->gt($hora_limite)){
            return $res->setCode(409)->setMessage("No puede cancelar la reserva")->send();
        }

        $reserva = DetalleDiario::find($id_reserva);

        try {
            $reserva->delete();
            $diario->actualizarTotalRaciones();
        } catch (\Exception $e) {
            return $res->setCode(409)->setMessage("No fué posible cancelar la reserva.")->send();
        }

        return $res->setMessage("Se elimino la reserva")->send();
    }

    public function cargarMenu(Request $request){
        $res = new ApiMessage;

        $validatedData = $request->validate([
            'menu_comida' => 'required|string|max:255',
        ]);

        $diario_actual = Diario::diarioActual();

        $diario_actual->update($validatedData);

        return $res->setData($diario_actual)->send();

    }

    public function showReservaActualByBecadoId(Request $request, $becado_id)
    {
        $res = new ApiMessage();

        $reserva =Becado::reservaActual($becado_id);

        if (!$reserva) {
             return $res->setCode(404)->setMessage('El becado no cuenta con una reserva para el diario actual')->send();
        }

        return $res->setData($reserva)->send();
    }

    public function changeAplicarFaltasToDiarioActual(Request $request )
    {
        $res = new ApiMessage($request);
        $data = $request->validate([
            'value' => 'required|boolean'
        ]);

        $new_val = boolval($data['value']);
        $diario_actual = Diario::diarioActual();
        if ($diario_actual->aplicar_faltas != $new_val) {
            $diario_actual->aplicar_faltas = $new_val;
            try {
                $diario_actual->saveOrFail();
                $res->setMessage("El diario ha sido actualizado");
            } catch (\Throwable $e) {
                AppLogs::addError("Se ha producido un error al actualizar el campo aplicar_faltas del diario actual.",$e,[
                    'diario_actual' => $diario_actual->id
                ]);
                return $res->setCode(409)->setMessage("No fué posible actualizar el diario actual.")->send();
            }
        }

        $res->setData([
            'new_val' => $diario_actual->aplicar_faltas
        ]);

        return $res->send();
    }

}
