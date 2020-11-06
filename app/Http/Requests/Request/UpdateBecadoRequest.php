<?php

namespace App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBecadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #return $this->user()->Isadmin();
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
            'dni' => 'numeric|digits_between:8,9',
            'nombres' => 'string|max:30',
            'apellidos' => 'string|max:50',
            'email' => 'email',
            'telefono'=> 'numeric|digits_between:8,20',
        ];
    }
}
