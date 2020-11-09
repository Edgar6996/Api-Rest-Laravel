<?php

namespace App\Http\Requests;

use App\Enums\TiposUsuarios;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class RegistroUsuariosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|string|max:100",
            'email' => "required|email|unique:users",
            'username' => "required|string|unique:users",
            'password' => "required|string|max:250",
            'rol' => ['required', new EnumValue(TiposUsuarios::class)],
        ];
    }
}
