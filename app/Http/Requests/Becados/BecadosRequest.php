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

        $rules = [
            'dni' => 'required|numeric|unique:becados',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:users',

            'size_template' => 'required|numeric',
            'img_width' => 'required|numeric',
            'img_height' => 'required|numeric'
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
