<?php

namespace App\Core\Services;

use App\Core\Tools\ApiMessage;
use App\Enums\LogTypes;
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
            'raciones_sin_retirar' => 0,
    	]);

        $this->crearDetalleDiario($diario_prox);

        \DB::commit();
        return $diario_prox;
    }

    public function cerrarDiario(Diario $diario){
        $lista_faltas = $diario->detalleDiario()
              ->where('retirado','=', 0)->get();

        $contador = 0;
        $_logs = [];
        foreach($lista_faltas as $reserva){

          $becado = $reserva->becado()->first();
          $becado->increment('total_faltas');

        }
        $total_faltas = count($lista_faltas);
        AppLogs::add("Se registraron ".$total_faltas." faltas. Suspendido: ".$contador,LogTypes::INFO,[
            'logs' => $_logs
        ]);

        $diario->racionesSinRetirar();

    }

    public function resetDiarioActual($resetFaltas = true)
    {
        $diario = Diario::diarioActual();
        try{
            // eliminamos el detalle
            $diario->detalleDiario()->delete();

            $this->crearDetalleDiario($diario, $resetFaltas);

            $diario->actualizarTotalRaciones();

            AppLogs::add("Se ha reseteado el diario #{$diario->id}", LogTypes::INFO);
            return true;
        }catch (\Throwable $e){
            AppLogs::add("Falló al resetear el diario #{$diario->id}", LogTypes::ERROR,[
                'error' => $e->getMessage(),
                'line' => $e->getFile() . ':' . $e->getLine()
            ]);
        }
        return false;
    }

    private function suspenderBecado(Becado $becado){
        $dias_castigo = AppConfig::getConfig()->castigo_duracion_dias;

        $suspendido_hasta = now()
            ->addDays($dias_castigo)
            ->subMinutes(5);

        $becado->suspendido_hasta = $suspendido_hasta;

        // Al suspender un becado, reseteamos el contador de faltas
        $becado->total_faltas = 0;

        $becado->save();

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

    /**
     * Genera todas las reservas para el diario indicado, de todos los becados ACTIVOS que van a comer en ese diario.
     * @param Diario $diario
     */
    private function crearDetalleDiario(Diario $diario, $resetFaltas = false){
      #buscamos los becados, ACTIVOS, que comen en el dia actual
      $lista_becados = Becado::activos()->whereHas('calendario', function($query) use($diario){
          $query->where($diario->horario_comida, '>', 0); //todos los becados que tienen en su calendario en el campo raciones mayor a cero
      })->with('calendario:id,becado_id,'.$diario->horario_comida)->get();

      $key_dia = $diario->horario_comida;


      $logs = [];
      $suspendidos = 0;
      if(!$lista_becados){
          $logs[] = "No se obtuvieron becados para esta comida";
      }
      foreach ($lista_becados as $becado) {
          # verificamos si becado tiene faltas
          if (!$resetFaltas && $becado->total_faltas > 0) {
              // tiene faltas, no puede comer
              // le descontamos la falta
              $becado->decrement('total_faltas');
              $becado->suspendido_hasta = $diario->fecha
                  ->clone()->addHours(2);

              $logs[] = "Becado #{$becado->id} suspendido hasta ". $becado->suspendido_hasta->toDateTimeString();
              $becado->save();
              $suspendidos++;

          }else{
              if($resetFaltas && ($becado->total_faltas > 0 || $becado->suspendido_hasta)){
                  $becado->total_faltas = 0;
                  $becado->suspendido_hasta = null;

                  $logs[] = "Se han borrado las faltas del becado #{$becado->id}";
                  $becado->save();
              }

              $diario->detalleDiario()->create([
                  'becado_id' => $becado->id,
                  'raciones' => $becado->calendario->$key_dia
              ]);
          }
      }


        $count = count($logs);
        if($count > 0){
          AppLogs::add("{$suspendidos} becados han sido penalizados por tener faltas", LogTypes::INFO,[
              'logs' => $logs
          ]);
      }


    }
}
