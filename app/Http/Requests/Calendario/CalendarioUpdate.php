<?php

namespace App\Http\Requests\Calendario;

use App\Enums\CategoriasBecados;
use App\Models\AppConfig;
use App\Models\Becado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class CalendarioUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        # Vamos a determinar si el becado al que le pertenece el calendario es un becado comun o es quirofano, para poder
        # definir el maximo de raciones permitidas

        # Desde aca, con $this podemos acceder a las variables que definimos en la ruta (con el nombre exacto)
        $calendarioId = $this->calendarioId;
        $becado = Becado::findByCalendarioId($calendarioId);

        if(!$becado){
            #abort(404);
            $max_raciones = 100;
        }else{
            if ($becado->categoria == CategoriasBecados::BECADO) {
                $max_raciones = AppConfig::getConfig()->max_porciones_becado;

            }else{
                $max_raciones = AppConfig::getConfig()->max_porciones_quirofano;
            }
        }

        $dias = [
            "lunes",
            "martes",
            "miercoles",
            "jueves",
            "viernes",
            "sabado",
            "domingo",
          ];
          $rules = [];
          foreach($dias as $key => $dia){
              $rules[$dia."_dia"] = "bail|numeric|between:0,{$max_raciones}";
              $rules[$dia."_noche"] = "bail|numeric|between:0,{$max_raciones}";
          }

        return $rules;
    }
}
