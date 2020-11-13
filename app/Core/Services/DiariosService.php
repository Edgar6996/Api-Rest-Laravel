<?php

namespace App\Core\Services;

use App\Core\Tools\ApiMessage;
use App\Models\AppConfig;
use App\Models\AppLogs;
use App\Models\Becado;
use App\Models\Calendario;
use App\Models\DetalleDiario;
use App\Models\Diario;
use Carbon\Carbon;



class DiariosService
{
  public function procesarDiarios(){
    try{
      $diario_actual = Diario::diarioActual();
      $this->cerrarDiario($diario_actual);
      
      $item = $this->generarProximoDiario();
      AppLogs::add("Nuevo diario creado: ". $item->horario_comida);
  }catch (\Exception $e){
      AppLogs::addError("Se ha producido un error al crear el próximo diario.",$e);
  }
  }

    /**
     * @return Diario
     * @throws \Exception
     */
	public function generarProximoDiario()
  	{
        $diario_actual = Diario::diarioActual();

    	$fecha_diario = $this->proximoComida();

        if($diario_actual != NULL && $fecha_diario->eq($diario_actual->fecha)){
            throw new \Exception("ya se ha generado el diario");
        }

        \DB::beginTransaction();

    	$diario_prox = Diario::create([
        'fecha' => $fecha_diario,
        'horario_comida' => $this->keyDia(),
        'total_raciones' => 0,
        'menu_comida' => "",
    	]);

        $this->crearDetalleDiario($diario_prox);

        \DB::commit();
        return $diario_prox;
    }
    
    public function cerrarDiario(Diario $diario){
        $lista_faltas = $diario->detalleDiario()
              ->where('retirado','=', 0)->get();

        $limite_faltas = AppConfig::getConfig()->max_faltas;

        $contador = 0;
        foreach($lista_faltas as $reserva){

          $becado = $reserva->becado()->first();
          $becado->increment('total_faltas');

          if ($becado->total_faltas >= $limite_faltas) {
              $contador++;
              $this->suspenderBecado($becado);
          }
        }
        $total_faltas = count($lista_faltas); 
        AppLogs::add("Se registraron ".$total_faltas." suspendido: ".$contador);
    }

    private function suspenderBecado($becado){

    }

  	private function proximoComida()
  	{
        //configuraciones
        $almuerzo = Carbon::parse(AppConfig::getConfig()->hora_almuerzo);
        $cena = Carbon::parse(AppConfig::getConfig()->hora_cena);

  		$hs_actual = Carbon::now();
  		$hs_almuerzo = Carbon::now()->setTime($almuerzo->hour,$almuerzo->minute,$almuerzo->second);
  		$hs_cena = Carbon::now()->setTime($cena->hour,$cena->minute,$cena->second);

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

      # Al nombre del día, tenemos que quitarle todas las tíldes.
        $nombre_dia = str_replace(
            ['miércoles', 'sábado'],
            ['miercoles', 'sabado'],
            $nombre_dia
        );


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
