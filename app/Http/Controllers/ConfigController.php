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
        $config = AppConfig::getConfig();

        $validateData = $request->validate([
            'max_porciones_becado' => 'numeric',
            'max_porciones_quirofano' => 'numeric',
    
            'max_faltas' => 'numeric',
            'castigo_duracion_dias' => 'numeric',
    
            'limite_horas_cancelar_reserva' => 'date_format:"H:i:s"',
            'hora_cena'=> 'date_format:"H:i:s"',
            'hora_almuerzo' => 'date_format:"H:i:s"'
        ]);

        

        $config->update($validateData);

        return $res->setData($config)->send();
    }
}
