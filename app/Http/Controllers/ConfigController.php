<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Models\AppConfig;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function show()
    {
        $res = new ApiMessage();
        $config = AppConfig::getConfig();

        return $res->setData($config)->send();
    }

    public function update(Request $request){

        $res = new ApiMessage;

        $validateData = $request->validate([
            'max_porciones_becado' => 'int|max:3',
            'max_porciones_quirofano' => 'int|max:100',
    
            'max_faltas' => 'int|max:3',
            'castigo_duracion_dias' => 'int|max:10',
    
            'limite_horas_cancelar_reserva' => 'date',
            'hora_cena'=> 'date',
            'hora_almuerzo' => 'date'
        ]);

        $config = AppConfig::getConfig();

        $config->update($validateData);

        return $res->setData($config)->send();
    }
}
