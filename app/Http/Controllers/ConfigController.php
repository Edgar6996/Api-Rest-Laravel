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
            'max_porciones_becado' => 'numeric',
            'max_porciones_quirofano' => 'numeric',
    
            'max_faltas' => 'numeric',
            'castigo_duracion_dias' => 'numeric',
    
            'limite_horas_cancelar_reserva' => 'date',
            'hora_cena'=> 'date',
            'hora_almuerzo' => 'date'
        ]);

        $config = AppConfig::getConfig();

        $config->update($validateData);

        return $res->setData($config)->send();
    }
}
