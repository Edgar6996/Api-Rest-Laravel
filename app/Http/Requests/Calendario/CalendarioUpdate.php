<?php

namespace App\Http\Requests\Calendario;

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
              $rules[$dia."_dia"] = 'bail|numeric|between:0,2';
              $rules[$dia."_noche"] = 'bail|numeric|between:0,2';
          }
          
        return $rules;
    }
}
