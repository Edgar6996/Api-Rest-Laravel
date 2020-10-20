<?php

namespace App\Http\Controllers;

use App\Core\Tools\ApiMessage;
use App\Http\Requests\Calendario\CalendarioUpdate;
use App\Models\Calendario;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($calendarioId)
    {
        $res = new ApiMessage();

        $res->addLog('Obteniendo calendario de la db');

        // Obtengo el calendario de becado

        $calendarioBecado = Calendario::findOrFail($calendarioId);




        $res->setData($calendarioBecado);

        return $res->send();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CalendarioUpdate $request, $calendarioId)
    {
        $res = new ApiMessage();
        $calendarioActualizado = $request->validated();

        // Obtengo el calendario de becado
        $calendarioBecado = Calendario::findOrFail($calendarioId);

        $res->addLog("Obteniendo calendario becado_id: $calendarioBecado->becado_id");

        // Insertamos datos actualizados
        $calendarioBecado->update($calendarioActualizado);

        try {
            // Guardamos y enviamos los datos
            $calendarioBecado->saveOrFail();
            $calendarioBecado->refresh();
            $res->setData($calendarioBecado);
            $res->setMessage('Calendario actualizado');
        } catch (\Throwable $th) {
            $res->setMessage('No fue posible actualizar el calendario');
            $res->addError($th->getMessage());
            $res->setCode(409);
        }

        return $res->send();
    }

}
