<?php

namespace App\Http\Requests\Becados;

use Illuminate\Foundation\Http\FormRequest;

class BecadosRequest extends FormRequest
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
        $MAX_RACIONES = 100;

        # Nota: no validamos el unique de DNI ni de Email porque en el metodo @store, si ya existe, se
        #       procede a hacer un update del becado.
        $rules = [
            'dni' => 'required|numeric',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono'=> 'required|numeric',
            'autorizado_por' => 'required|string|max:255',


        ];

        $dias = [
            "lunes",
            "martes",
            "miercoles",
            "jueves",
            "viernes",
            "sabado",
            "domingo",
        ];

        foreach($dias as $dia){
            $rules["calendario.{$dia}_dia"] = "required|numeric|between:0,$MAX_RACIONES";
            $rules["calendario.{$dia}_noche"] = "required|numeric|between:0,$MAX_RACIONES";
        }
        # dd($rules);
        return $rules;
    }
}
