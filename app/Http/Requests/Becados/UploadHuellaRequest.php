<?php

namespace App\Http\Requests\Becados;

use Illuminate\Foundation\Http\FormRequest;

class UploadHuellaRequest extends FormRequest
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
            'size_template' => 'required|numeric',
            'img_width' => 'required|numeric',
            'img_height' => 'required|numeric',

            'img_huella' => 'required',
            'template_huella' => 'required'
        ];
    }
}
