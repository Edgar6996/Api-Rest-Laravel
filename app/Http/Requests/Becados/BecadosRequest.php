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
        return [
            'dni' => 'required|numeric',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'raciones_dia' => 'required',
            'raciones_noche' => 'required'
        ];
    }
}
