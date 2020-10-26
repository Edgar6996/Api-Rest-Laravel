<?php

namespace App\Core\Services;

use App\Core\Tools\ApiMessage;
use App\Models\Calendario;
use App\Models\Diario;
use Carbon\Carbon;


class DiariosService
{

	public function generarProximoDiario()
  	{

    	$fecha_diario = $this->proximoComida();

    	$fields = Diario::create([
        'fecha' => $fecha_diario,
        'horario_comida' => $this->key_dia(),
        'total_raciones' => 10,
    	]);

    	return $fields;
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

    private function key_dia(){

      $fecha_diario = $this->proximoComida();

      $nombre_dia = $fecha_diario->dayName;

      $tipo = $fecha_diario->hour>12 ?  'noche':'dia';

      $key_dia = $nombre_dia."_".$tipo;

      return $key_dia;
    }

    private function total_raciones(){
      $fields = Calendario::all()
    }
}