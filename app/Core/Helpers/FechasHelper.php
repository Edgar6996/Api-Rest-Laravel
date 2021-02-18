<?php


namespace App\Core\Helpers;


use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
use Exception;

abstract class FechasHelper
{
    public const DEFAULT_FORMAT = 'Y-m-d H:i:s';
    public const LOCAL_FORMAT = 'd/m/Y H:i:s';
    public const LOCAL_FORMAT_DATE = 'd/m/Y';




    /***
     * Devuelve el nombre del día (en español) a partir del número
     * @param int $num_dia
     * @return string
     */
    public static function getNombreDia(int $num_dia):string
    {
        if($num_dia< 1 || $num_dia > 7){
            return "Desconocido";
        }
        return str_replace(
            array('1', '2', '3', '4', '5', '6', '7'),
            array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'),
            "{$num_dia}"
        );
    }


    /***
     * Dada una hora en formato H:i, le agrega los minutos indicados y devuelve
     * el resultado. En caso de que la hora sea incorrecta, devuelve FALSE.
     * @param string $hora
     * @param int $minutes_to_add
     * @return bool|string
     */
    public static function addMinutosToHora(string $hora, int $minutes_to_add)
    {
        try {
            $time = new DateTime($hora);


            if($minutes_to_add < 0){
                $minutes_to_add = $minutes_to_add * -1;
                $interval = new DateInterval('PT' . $minutes_to_add . 'M');
                $interval->invert = 1;
            }else{
                $interval = new DateInterval('PT' . $minutes_to_add . 'M');
            }


            $time->add($interval);
            $stamp = $time->format('H:i');
            return $stamp;
        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * Dadas dos horas, valída que la primera sea menor que la segunda.
     * @param $horaInicio
     * @param $horaFin
     * @return bool
     */
    public static function validarIntervaloHoras($horaInicio, $horaFin)
    {
        if(strtotime($horaInicio)<strtotime($horaFin)) {
            //do some work
            return true;
        } else {
            //do something
            return false;
        }
    }


    /**
     * Obtiene el total de minutos que tiene en un intervalo de tiempo-
     * @param DateTime|DateInterval $time
     * @return float|int
     */
    public static function pasarHorasaMinutos( $time)
    {
       // var minutos = d.getHours() * 60 + d.getMinutes();

        if($time instanceof DateInterval){

            $horas = (int)$time->format('%H');
            $minutos = (int)$time->format('%i');
        }else{

            $horas = (int)$time->format('H');
            $minutos = (int)$time->format('i');
        }



        $minutos_res = $horas*60 + $minutos;

        return $minutos_res;

    }

    /**
     * Verifica si las horas de $duracion es menor que el intervalo de tiempo comprendido entre $horaInicio y $horaFin
     * @param string $duracion
     * @param string $horaInicio
     * @param string $horaFin
     * @return bool
     * @throws Exception
     */
    public static function validarDuracionConsulta(string $duracion, string $horaInicio, string $horaFin): bool
    {
        $duracionConsulta = new DateTime($duracion);
        $minutoToAdd = FechasHelper::pasarHorasaMinutos($duracionConsulta);

        #calculamos el maximo intervalo posible
        $datetime1 = new DateTime($horaInicio);
        $datetime2 = new DateTime($horaFin);
        $interval = $datetime1->diff($datetime2);

        $max_minutos = FechasHelper::pasarHorasaMinutos($interval);

        if($minutoToAdd > $max_minutos)
        {
            return  false;
        }
        return  true;
    }

    /**
     * Obtiene la fecha correspondiente al primer día del mes.
     * @param int $mes
     * @param int|null $year
     * @return false|string
     */
    public static function obtenerPrimerDiaMes(int $mes, int $year = null)
    {
        $month = $mes;
        if(!is_numeric($year)){
            $year = date('Y');
        }

        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    }

    /**
     * Obtiene la fecha correspondiente al último día del mes.
     * @param int $mes
     * @param int|null $year
     * @return false|string
     */
    public static function obtenerUltimoDiaMes(int $mes,int $year = null)
    {
        $month = $mes;
        if(!is_numeric($year)){
            $year = date('Y');
        }

        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }

    /**
     * Obtiene el equivalente numero del dia de la semana de una fecha.
     * @param string $fecha
     * @return false|int|string
     */
    public static function obetenerNumeroDeDiaSemana(string $fecha)
    {
        $dayofweek = date('w', strtotime($fecha));
        # $result    = date('Y-m-d', strtotime(($day - $dayofweek).' day', strtotime($date)));
        return $dayofweek + 1;
    }

    /**
     * Dadas dos horas, obtiene la diferencia de tiempo que existe entre ellas, en minutos.
     * @param string $hora_inicio
     * @param string $hora_fin
     * @return bool|float|int
     */
    public static function getMinutosIntervalo(string $hora_inicio, string $hora_fin)
    {
        try {
            $d1 = new DateTime($hora_inicio);
            $d2 = new DateTime($hora_fin);
            $diff=$d2->diff($d1);

            return self::pasarHorasaMinutos($diff);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene una cadena de tiempo a partir de una instancia CarbonInterval.
     * El formato es H:i:m.u
     * @param CarbonInterval $d
     * @return string
     */
    public static function getTimeFormatFromCarbonInterval(CarbonInterval $d):string
    {
        $h = str_pad($d->hours,2,'0');
        $m = str_pad($d->minutes,2,'0');
        $s = str_pad($d->seconds,2,'0');
        $mm = str_pad($d->milliseconds,2,'0');

        return "{$h}:{$m}:{$s}.{$mm}";
    }
}
