<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Enums\CategoriasBecados;
use App\Models\AppConfig;
use App\Models\Calendario;
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
        
        $max_porciones_becados_anterior = $config->max_porciones_becado;
        $max_porciones_quirofano_anterior = $config->max_porciones_quirofano;

        $config->update($validateData);

        if ($validateData['max_porciones_becado']<$max_porciones_becados_anterior) 
        {   
            $categoria = CategoriasBecados::BECADO;
            intval($categoria);
            Calendario::actualizarLimiteDeRaciones($categoria,$config->max_porciones_becado);
        }

        if ($validateData['max_porciones_quirofano']<$max_porciones_quirofano_anterior) 
        {
            $categoria = CategoriasBecados::QUIROFANO;
            intval($categoria);
            Calendario::actualizarLimiteDeRaciones($categoria,$config->max_porciones_quirofano);
        }
        

        return $res->setData($config)->send();
    }

}
