<?php

namespace App\Core\Services;

use App\Core\Tools\ApiMessage;
use App\Models\Becado;
use App\Models\Calendario;
use App\Models\Diario;
use Carbon\Carbon;


class DiariosService
{

	public function generarProximoDiario()
  	{

      $diario_actual = Diario::diarioActual();

    	$fecha_diario = $this->proximoComida();

      if($diario_actual != NULL && $fecha_diario->eq($diario_actual->fecha)){
          throw new \Exception("ya se ha generado el diario");
      }

      \DB::beginTransaction();

    	$fields = Diario::create([
        'fecha' => $fecha_diario,
        'horario_comida' => $this->keyDia(),
        'total_raciones' => 0,
    	]);

      $this->crearDetalleDiario($fields);

      \DB::commit();
  	}

  	private function proximoComida()
  	{

  		$hs_actual = Carbon::now();
  		$hs_almuerzo = Carbon::now()->setTime(12,0,0);
  		$hs_cena = Carbon::now()->setTime(20,0,0);

  		if($hs_almuerzo->gt($hs_actual)){
  			return $hs_almuerzo;
  		}elseif($hs_cena->gt($hs_actual)){
  			return $hs_cena;
  		}else{
  			return $hs_almuerzo->addDays(1);
  		}
  	}

    private function keyDia(){

      $fecha_diario = $this->proximoComida();

      $nombre_dia = $fecha_diario->dayName;

      $tipo = $fecha_diario->hour>12 ?  'noche':'dia';

      $key_dia = $nombre_dia."_".$tipo;

      return $key_dia;
    }

    private function crearDetalleDiario(Diario $diario){
      #buscamos los becados que comen en el dia actual
      $lista_becados = Becado::whereHas('calendario', function($query) use($diario){
          $query->where($diario->horario_comida, '>', 0); //todos los becados que tienen en su calendario en el campo raciones mayor a cero
      })->with('calendario:id,becado_id,'.$diario->horario_comida)->get();

      $key_dia = $diario->horario_comida;

      foreach ($lista_becados as $becado) {
        $diario->detalleDiario()->create([
            'becado_id' => $becado->id,
            'raciones' => $becado->calendario->$key_dia
        ]);
      }
    }
}
